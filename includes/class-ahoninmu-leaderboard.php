<?php
/**
 * Leaderboard Management Class
 * Handles ranking calculations and leaderboard display
 */

if (!defined('ABSPATH')) {
    exit;
}

class Ahoninmu_Leaderboard {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // No immediate actions needed
    }
    
    /**
     * Update user ranking for current month
     */
    public static function update_user_ranking($user_id) {
        global $wpdb;
        
        $year_month = current_time('Y-m');
        $rankings_table = $wpdb->prefix . 'ahoninmu_rankings';
        
        // Calculate missions completed and total time for the month
        $query = "
            SELECT 
                COUNT(*) as missions_completed,
                SUM(up.completion_time) as total_time,
                AVG(up.completion_time) as average_time
            FROM {$wpdb->prefix}ahoninmu_user_progress up
            INNER JOIN {$wpdb->prefix}ahoninmu_daily_missions dm ON up.daily_mission_id = dm.id
            WHERE up.user_id = %d 
            AND up.completed = 1
            AND DATE_FORMAT(dm.mission_date, '%%Y-%%m') = %s
        ";
        
        $stats = $wpdb->get_row($wpdb->prepare($query, $user_id, $year_month));
        
        $missions_completed = $stats->missions_completed ? intval($stats->missions_completed) : 0;
        $total_time = $stats->total_time ? intval($stats->total_time) : 0;
        $average_time = $stats->average_time ? floatval($stats->average_time) : 0;
        
        // Calculate ranking score
        // Higher score = more missions completed + bonus for faster completion
        // Formula: (missions * 100) - (average_time / 10)
        $ranking_score = ($missions_completed * 100);
        if ($average_time > 0) {
            $ranking_score -= ($average_time / 10);
        }
        
        // Check if ranking exists
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT id FROM $rankings_table WHERE user_id = %d AND year_month = %s",
            $user_id,
            $year_month
        ));
        
        if ($existing) {
            // Update existing ranking
            $wpdb->update(
                $rankings_table,
                array(
                    'missions_completed' => $missions_completed,
                    'total_time' => $total_time,
                    'average_time' => $average_time,
                    'ranking_score' => $ranking_score
                ),
                array(
                    'user_id' => $user_id,
                    'year_month' => $year_month
                )
            );
        } else {
            // Insert new ranking
            $wpdb->insert(
                $rankings_table,
                array(
                    'user_id' => $user_id,
                    'year_month' => $year_month,
                    'missions_completed' => $missions_completed,
                    'total_time' => $total_time,
                    'average_time' => $average_time,
                    'ranking_score' => $ranking_score
                )
            );
        }
    }
    
    /**
     * Get monthly leaderboard
     */
    public function get_monthly_leaderboard($year_month = null, $limit = 20) {
        global $wpdb;
        
        if (!$year_month) {
            $year_month = current_time('Y-m');
        }
        
        $query = "
            SELECT 
                r.*,
                u.display_name,
                u.user_login,
                u.user_email
            FROM {$wpdb->prefix}ahoninmu_rankings r
            INNER JOIN {$wpdb->users} u ON r.user_id = u.ID
            WHERE r.year_month = %s
            ORDER BY r.ranking_score DESC, r.missions_completed DESC, r.average_time ASC
            LIMIT %d
        ";
        
        return $wpdb->get_results($wpdb->prepare($query, $year_month, $limit));
    }
    
    /**
     * Get user's current rank
     */
    public function get_user_rank($user_id, $year_month = null) {
        global $wpdb;
        
        if (!$year_month) {
            $year_month = current_time('Y-m');
        }
        
        // Get user's ranking score
        $user_score = $wpdb->get_var($wpdb->prepare(
            "SELECT ranking_score FROM {$wpdb->prefix}ahoninmu_rankings 
             WHERE user_id = %d AND year_month = %s",
            $user_id,
            $year_month
        ));
        
        if ($user_score === null) {
            return null;
        }
        
        // Count how many users have a higher score
        $rank = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) + 1 FROM {$wpdb->prefix}ahoninmu_rankings 
             WHERE year_month = %s AND ranking_score > %f",
            $year_month,
            $user_score
        ));
        
        return intval($rank);
    }
    
    /**
     * Get total number of participants for the month
     */
    public function get_total_participants($year_month = null) {
        global $wpdb;
        
        if (!$year_month) {
            $year_month = current_time('Y-m');
        }
        
        return intval($wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}ahoninmu_rankings WHERE year_month = %s",
            $year_month
        )));
    }
    
    /**
     * Format time in readable format
     */
    public static function format_time($seconds) {
        if ($seconds < 60) {
            return sprintf(__('%d giây', 'ahoninmu-japanese'), $seconds);
        } elseif ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            return sprintf(__('%d phút', 'ahoninmu-japanese'), $minutes);
        } else {
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            return sprintf(__('%d giờ %d phút', 'ahoninmu-japanese'), $hours, $minutes);
        }
    }
}
