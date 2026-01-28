<?php
/**
 * Admin Class
 * Handles admin panel functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class Ahoninmu_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_post_ahoninmu_add_mission', array($this, 'handle_add_mission'));
        add_action('admin_post_ahoninmu_delete_mission', array($this, 'handle_delete_mission'));
        add_action('admin_post_ahoninmu_generate_missions', array($this, 'handle_generate_missions'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Ahoninmu Japanese', 'ahoninmu-japanese'),
            __('Ahoninmu Japanese', 'ahoninmu-japanese'),
            'manage_options',
            'ahoninmu-japanese',
            array($this, 'dashboard_page'),
            'dashicons-welcome-learn-more',
            30
        );
        
        add_submenu_page(
            'ahoninmu-japanese',
            __('Nhiệm vụ', 'ahoninmu-japanese'),
            __('Nhiệm vụ', 'ahoninmu-japanese'),
            'manage_options',
            'ahoninmu-missions',
            array($this, 'missions_page')
        );
        
        add_submenu_page(
            'ahoninmu-japanese',
            __('Bảng xếp hạng', 'ahoninmu-japanese'),
            __('Bảng xếp hạng', 'ahoninmu-japanese'),
            'manage_options',
            'ahoninmu-leaderboard',
            array($this, 'leaderboard_page')
        );
        
        add_submenu_page(
            'ahoninmu-japanese',
            __('Cài đặt', 'ahoninmu-japanese'),
            __('Cài đặt', 'ahoninmu-japanese'),
            'manage_options',
            'ahoninmu-settings',
            array($this, 'settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('ahoninmu_settings', 'ahoninmu_missions_per_day');
        register_setting('ahoninmu_settings', 'ahoninmu_reset_time');
    }
    
    /**
     * Dashboard page
     */
    public function dashboard_page() {
        global $wpdb;
        
        // Get statistics
        $total_users = $wpdb->get_var("SELECT COUNT(DISTINCT user_id) FROM {$wpdb->prefix}ahoninmu_user_progress");
        $total_missions = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}ahoninmu_missions WHERE is_active = 1");
        $completed_today = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}ahoninmu_user_progress up
             INNER JOIN {$wpdb->prefix}ahoninmu_daily_missions dm ON up.daily_mission_id = dm.id
             WHERE up.completed = 1 AND dm.mission_date = %s",
            current_time('Y-m-d')
        ));
        
        $year_month = current_time('Y-m');
        $leaderboard_obj = Ahoninmu_Leaderboard::get_instance();
        $top_users = $leaderboard_obj->get_monthly_leaderboard($year_month, 5);
        
        include AHONINMU_PLUGIN_DIR . 'templates/admin-dashboard.php';
    }
    
    /**
     * Missions management page
     */
    public function missions_page() {
        global $wpdb;
        
        $missions = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ahoninmu_missions ORDER BY id DESC");
        
        include AHONINMU_PLUGIN_DIR . 'templates/admin-missions.php';
    }
    
    /**
     * Leaderboard page
     */
    public function leaderboard_page() {
        $year_month = isset($_GET['month']) ? sanitize_text_field($_GET['month']) : current_time('Y-m');
        
        // Validate year_month format
        if (!preg_match('/^\d{4}-\d{2}$/', $year_month)) {
            $year_month = current_time('Y-m');
        }
        
        $leaderboard_obj = Ahoninmu_Leaderboard::get_instance();
        $leaders = $leaderboard_obj->get_monthly_leaderboard($year_month, 100);
        
        include AHONINMU_PLUGIN_DIR . 'templates/admin-leaderboard.php';
    }
    
    /**
     * Settings page
     */
    public function settings_page() {
        include AHONINMU_PLUGIN_DIR . 'templates/admin-settings.php';
    }
    
    /**
     * Handle add mission
     */
    public function handle_add_mission() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        check_admin_referer('ahoninmu_add_mission');
        
        global $wpdb;
        
        $wpdb->insert(
            $wpdb->prefix . 'ahoninmu_missions',
            array(
                'title' => sanitize_text_field($_POST['title']),
                'description' => sanitize_textarea_field($_POST['description']),
                'mission_type' => sanitize_text_field($_POST['mission_type']),
                'difficulty' => sanitize_text_field($_POST['difficulty']),
                'points' => intval($_POST['points']),
                'estimated_time' => intval($_POST['estimated_time']),
                'is_active' => 1
            )
        );
        
        wp_redirect(admin_url('admin.php?page=ahoninmu-missions&success=1'));
        exit;
    }
    
    /**
     * Handle delete mission
     */
    public function handle_delete_mission() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        check_admin_referer('ahoninmu_delete_mission_' . $_GET['id']);
        
        global $wpdb;
        $wpdb->delete(
            $wpdb->prefix . 'ahoninmu_missions',
            array('id' => intval($_GET['id']))
        );
        
        wp_redirect(admin_url('admin.php?page=ahoninmu-missions&deleted=1'));
        exit;
    }
    
    /**
     * Handle manual mission generation
     */
    public function handle_generate_missions() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        check_admin_referer('ahoninmu_generate_missions');
        
        $missions_obj = Ahoninmu_Missions::get_instance();
        $missions_obj->generate_daily_missions();
        
        wp_redirect(admin_url('admin.php?page=ahoninmu-japanese&generated=1'));
        exit;
    }
}
