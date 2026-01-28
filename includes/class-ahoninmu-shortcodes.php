<?php
/**
 * Shortcodes Class
 * Handles all shortcodes for frontend display
 */

if (!defined('ABSPATH')) {
    exit;
}

class Ahoninmu_Shortcodes {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Register shortcodes
        add_shortcode('ahoninmu_missions', array($this, 'missions_shortcode'));
        add_shortcode('ahoninmu_progress', array($this, 'progress_shortcode'));
        add_shortcode('ahoninmu_leaderboard', array($this, 'leaderboard_shortcode'));
    }
    
    /**
     * Missions list shortcode
     * Usage: [ahoninmu_missions]
     */
    public function missions_shortcode($atts) {
        if (!is_user_logged_in()) {
            return '<div class="ahoninmu-notice">Vui lòng <a href="' . wp_login_url(get_permalink()) . '">đăng nhập</a> để xem nhiệm vụ của bạn.</div>';
        }
        
        $missions_obj = Ahoninmu_Missions::get_instance();
        $missions = $missions_obj->get_daily_missions();
        
        if (empty($missions)) {
            return '<div class="ahoninmu-notice">Không có nhiệm vụ nào cho hôm nay. Vui lòng quay lại sau.</div>';
        }
        
        ob_start();
        include AHONINMU_PLUGIN_DIR . 'templates/missions-list.php';
        return ob_get_clean();
    }
    
    /**
     * Progress notification shortcode
     * Usage: [ahoninmu_progress]
     */
    public function progress_shortcode($atts) {
        if (!is_user_logged_in()) {
            return '';
        }
        
        $missions_obj = Ahoninmu_Missions::get_instance();
        $summary = $missions_obj->get_user_progress_summary();
        
        ob_start();
        include AHONINMU_PLUGIN_DIR . 'templates/progress-notification.php';
        return ob_get_clean();
    }
    
    /**
     * Leaderboard shortcode
     * Usage: [ahoninmu_leaderboard limit="20"]
     */
    public function leaderboard_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 20,
            'month' => current_time('Y-m')
        ), $atts);
        
        $leaderboard_obj = Ahoninmu_Leaderboard::get_instance();
        $leaders = $leaderboard_obj->get_monthly_leaderboard($atts['month'], intval($atts['limit']));
        
        $current_user_id = get_current_user_id();
        $user_rank = null;
        
        if ($current_user_id) {
            $user_rank = $leaderboard_obj->get_user_rank($current_user_id, $atts['month']);
        }
        
        ob_start();
        include AHONINMU_PLUGIN_DIR . 'templates/leaderboard.php';
        return ob_get_clean();
    }
}
