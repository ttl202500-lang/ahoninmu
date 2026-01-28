<?php
/**
 * Missions Management Class
 * Handles daily mission generation, reset, and completion tracking
 */

if (!defined('ABSPATH')) {
    exit;
}

class Ahoninmu_Missions {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Hook for daily mission reset
        add_action('ahoninmu_daily_reset', array($this, 'generate_daily_missions'));
        
        // AJAX handlers for mission completion
        add_action('wp_ajax_ahoninmu_complete_mission', array($this, 'ajax_complete_mission'));
        add_action('wp_ajax_ahoninmu_start_mission', array($this, 'ajax_start_mission'));
    }
    
    /**
     * Add default mission templates
     */
    public static function add_default_missions() {
        global $wpdb;
        $table = $wpdb->prefix . 'ahoninmu_missions';
        
        // Check if missions already exist
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
        if ($count > 0) {
            return;
        }
        
        $default_missions = array(
            array(
                'title' => 'Học 10 từ vựng mới',
                'description' => 'Học và ghi nhớ 10 từ vựng tiếng Nhật mới trong ngày',
                'mission_type' => 'vocabulary',
                'difficulty' => 'easy',
                'points' => 10,
                'estimated_time' => 10
            ),
            array(
                'title' => 'Luyện viết Hiragana',
                'description' => 'Viết 20 ký tự Hiragana để cải thiện kỹ năng viết',
                'mission_type' => 'writing',
                'difficulty' => 'easy',
                'points' => 10,
                'estimated_time' => 8
            ),
            array(
                'title' => 'Luyện viết Katakana',
                'description' => 'Viết 20 ký tự Katakana để cải thiện kỹ năng viết',
                'mission_type' => 'writing',
                'difficulty' => 'easy',
                'points' => 10,
                'estimated_time' => 8
            ),
            array(
                'title' => 'Đọc đoạn văn ngắn',
                'description' => 'Đọc và hiểu một đoạn văn tiếng Nhật ngắn (100-150 chữ)',
                'mission_type' => 'reading',
                'difficulty' => 'medium',
                'points' => 15,
                'estimated_time' => 12
            ),
            array(
                'title' => 'Nghe và lặp lại',
                'description' => 'Nghe 5 câu tiếng Nhật và lặp lại chính xác',
                'mission_type' => 'listening',
                'difficulty' => 'medium',
                'points' => 15,
                'estimated_time' => 10
            ),
            array(
                'title' => 'Học 5 Kanji cơ bản',
                'description' => 'Học và ghi nhớ 5 chữ Kanji cơ bản',
                'mission_type' => 'kanji',
                'difficulty' => 'medium',
                'points' => 20,
                'estimated_time' => 15
            ),
            array(
                'title' => 'Tạo 3 câu tiếng Nhật',
                'description' => 'Viết 3 câu tiếng Nhật sử dụng ngữ pháp đã học',
                'mission_type' => 'grammar',
                'difficulty' => 'medium',
                'points' => 15,
                'estimated_time' => 12
            ),
            array(
                'title' => 'Luyện phát âm',
                'description' => 'Luyện phát âm 10 từ khó trong tiếng Nhật',
                'mission_type' => 'pronunciation',
                'difficulty' => 'medium',
                'points' => 15,
                'estimated_time' => 10
            ),
            array(
                'title' => 'Dịch câu đơn giản',
                'description' => 'Dịch 5 câu từ tiếng Việt sang tiếng Nhật',
                'mission_type' => 'translation',
                'difficulty' => 'hard',
                'points' => 20,
                'estimated_time' => 15
            ),
            array(
                'title' => 'Trò chuyện 5 phút',
                'description' => 'Thực hành hội thoại tiếng Nhật trong 5 phút',
                'mission_type' => 'conversation',
                'difficulty' => 'hard',
                'points' => 25,
                'estimated_time' => 15
            ),
            array(
                'title' => 'Ôn tập ngữ pháp',
                'description' => 'Ôn tập và làm bài tập về một điểm ngữ pháp đã học',
                'mission_type' => 'grammar',
                'difficulty' => 'medium',
                'points' => 15,
                'estimated_time' => 12
            ),
            array(
                'title' => 'Xem video học tập',
                'description' => 'Xem một video học tiếng Nhật và ghi chú các điểm quan trọng',
                'mission_type' => 'video',
                'difficulty' => 'easy',
                'points' => 10,
                'estimated_time' => 15
            ),
            array(
                'title' => 'Học thành ngữ',
                'description' => 'Học và hiểu 3 thành ngữ tiếng Nhật phổ biến',
                'mission_type' => 'idioms',
                'difficulty' => 'hard',
                'points' => 20,
                'estimated_time' => 10
            ),
            array(
                'title' => 'Làm quiz từ vựng',
                'description' => 'Hoàn thành bài kiểm tra từ vựng với ít nhất 80% đúng',
                'mission_type' => 'quiz',
                'difficulty' => 'medium',
                'points' => 15,
                'estimated_time' => 10
            ),
            array(
                'title' => 'Đọc manga',
                'description' => 'Đọc 2-3 trang manga tiếng Nhật và hiểu nội dung',
                'mission_type' => 'reading',
                'difficulty' => 'medium',
                'points' => 15,
                'estimated_time' => 12
            )
        );
        
        foreach ($default_missions as $mission) {
            $wpdb->insert($table, $mission);
        }
    }
    
    /**
     * Generate daily missions for today
     */
    public function generate_daily_missions() {
        global $wpdb;
        
        $today = current_time('Y-m-d');
        $daily_table = $wpdb->prefix . 'ahoninmu_daily_missions';
        
        // Check if missions already exist for today
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $daily_table WHERE mission_date = %s",
            $today
        ));
        
        if ($existing > 0) {
            return; // Missions already generated for today
        }
        
        // Get all active missions
        $missions_table = $wpdb->prefix . 'ahoninmu_missions';
        $missions = $wpdb->get_results(
            "SELECT id FROM $missions_table WHERE is_active = 1 ORDER BY RAND()"
        );
        
        if (empty($missions)) {
            return;
        }
        
        // Select configured number of random missions (or fewer if not enough available)
        $missions_per_day = get_option('ahoninmu_missions_per_day', 10);
        $selected_missions = array_slice($missions, 0, intval($missions_per_day));
        
        // Insert daily missions
        $order = 1;
        foreach ($selected_missions as $mission) {
            $wpdb->insert(
                $daily_table,
                array(
                    'mission_date' => $today,
                    'mission_id' => $mission->id,
                    'mission_order' => $order++
                )
            );
        }
    }
    
    /**
     * Get today's missions for a user
     */
    public function get_daily_missions($user_id = null) {
        global $wpdb;
        
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        $today = current_time('Y-m-d');
        
        $query = "
            SELECT 
                dm.id as daily_mission_id,
                dm.mission_order,
                m.id as mission_id,
                m.title,
                m.description,
                m.mission_type,
                m.difficulty,
                m.points,
                m.estimated_time,
                up.completed,
                up.completion_time,
                up.completed_at,
                up.created_at as started_at
            FROM {$wpdb->prefix}ahoninmu_daily_missions dm
            INNER JOIN {$wpdb->prefix}ahoninmu_missions m ON dm.mission_id = m.id
            LEFT JOIN {$wpdb->prefix}ahoninmu_user_progress up 
                ON dm.id = up.daily_mission_id AND up.user_id = %d
            WHERE dm.mission_date = %s
            ORDER BY dm.mission_order ASC
        ";
        
        return $wpdb->get_results($wpdb->prepare($query, $user_id, $today));
    }
    
    /**
     * Start a mission (record start time)
     */
    public function ajax_start_mission() {
        check_ajax_referer('ahoninmu_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Bạn cần đăng nhập để bắt đầu nhiệm vụ.'));
        }
        
        $user_id = get_current_user_id();
        $daily_mission_id = intval($_POST['daily_mission_id']);
        
        global $wpdb;
        $table = $wpdb->prefix . 'ahoninmu_user_progress';
        
        // Check if already started
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d AND daily_mission_id = %d",
            $user_id,
            $daily_mission_id
        ));
        
        if ($existing) {
            wp_send_json_success(array('message' => 'Nhiệm vụ đã được bắt đầu trước đó.'));
        }
        
        // Insert start record
        $result = $wpdb->insert(
            $table,
            array(
                'user_id' => $user_id,
                'daily_mission_id' => $daily_mission_id,
                'completed' => 0
            )
        );
        
        if ($result) {
            wp_send_json_success(array('message' => 'Đã bắt đầu nhiệm vụ!'));
        } else {
            wp_send_json_error(array('message' => 'Không thể bắt đầu nhiệm vụ.'));
        }
    }
    
    /**
     * Complete a mission
     */
    public function ajax_complete_mission() {
        check_ajax_referer('ahoninmu_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Bạn cần đăng nhập để hoàn thành nhiệm vụ.'));
        }
        
        $user_id = get_current_user_id();
        $daily_mission_id = intval($_POST['daily_mission_id']);
        
        global $wpdb;
        $table = $wpdb->prefix . 'ahoninmu_user_progress';
        
        // Get start time
        $progress = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d AND daily_mission_id = %d",
            $user_id,
            $daily_mission_id
        ));
        
        $completion_time = 0;
        if ($progress && $progress->created_at) {
            $start = strtotime($progress->created_at);
            $completion_time = time() - $start;
        }
        
        if ($progress) {
            // Update existing record
            $result = $wpdb->update(
                $table,
                array(
                    'completed' => 1,
                    'completion_time' => $completion_time,
                    'completed_at' => current_time('mysql')
                ),
                array(
                    'user_id' => $user_id,
                    'daily_mission_id' => $daily_mission_id
                )
            );
        } else {
            // Insert new record
            $result = $wpdb->insert(
                $table,
                array(
                    'user_id' => $user_id,
                    'daily_mission_id' => $daily_mission_id,
                    'completed' => 1,
                    'completion_time' => $completion_time,
                    'completed_at' => current_time('mysql')
                )
            );
        }
        
        if ($result !== false) {
            // Update monthly ranking
            Ahoninmu_Leaderboard::update_user_ranking($user_id);
            
            // Get updated progress
            $missions = $this->get_daily_missions($user_id);
            $completed = 0;
            $total = count($missions);
            foreach ($missions as $mission) {
                if ($mission->completed) {
                    $completed++;
                }
            }
            
            wp_send_json_success(array(
                'message' => 'Chúc mừng! Bạn đã hoàn thành nhiệm vụ.',
                'completed' => $completed,
                'total' => $total
            ));
        } else {
            wp_send_json_error(array('message' => 'Không thể hoàn thành nhiệm vụ.'));
        }
    }
    
    /**
     * Get user progress summary for today
     */
    public function get_user_progress_summary($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        $missions = $this->get_daily_missions($user_id);
        
        $total = count($missions);
        $completed = 0;
        
        foreach ($missions as $mission) {
            if ($mission->completed) {
                $completed++;
            }
        }
        
        return array(
            'completed' => $completed,
            'total' => $total,
            'percentage' => $total > 0 ? round(($completed / $total) * 100) : 0
        );
    }
}
