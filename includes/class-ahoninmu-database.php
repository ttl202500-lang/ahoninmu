<?php
/**
 * Database Management Class
 * Handles all database operations for the plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Ahoninmu_Database {
    
    /**
     * Create plugin database tables
     */
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Table for mission templates
        $missions_table = $wpdb->prefix . 'ahoninmu_missions';
        $missions_sql = "CREATE TABLE IF NOT EXISTS $missions_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text NOT NULL,
            mission_type varchar(50) NOT NULL,
            difficulty varchar(20) DEFAULT 'medium',
            points int(11) DEFAULT 10,
            estimated_time int(11) DEFAULT 5,
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY mission_type (mission_type),
            KEY is_active (is_active)
        ) $charset_collate;";
        
        // Table for daily mission assignments
        $daily_missions_table = $wpdb->prefix . 'ahoninmu_daily_missions';
        $daily_missions_sql = "CREATE TABLE IF NOT EXISTS $daily_missions_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            mission_date date NOT NULL,
            mission_id bigint(20) NOT NULL,
            mission_order int(11) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY mission_date (mission_date),
            KEY mission_id (mission_id),
            UNIQUE KEY unique_date_order (mission_date, mission_order)
        ) $charset_collate;";
        
        // Table for user progress
        $user_progress_table = $wpdb->prefix . 'ahoninmu_user_progress';
        $user_progress_sql = "CREATE TABLE IF NOT EXISTS $user_progress_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            daily_mission_id bigint(20) NOT NULL,
            completed tinyint(1) DEFAULT 0,
            completion_time int(11) DEFAULT NULL,
            completed_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY daily_mission_id (daily_mission_id),
            KEY completed (completed),
            UNIQUE KEY unique_user_mission (user_id, daily_mission_id)
        ) $charset_collate;";
        
        // Table for monthly rankings
        $rankings_table = $wpdb->prefix . 'ahoninmu_rankings';
        $rankings_sql = "CREATE TABLE IF NOT EXISTS $rankings_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            year_month varchar(7) NOT NULL,
            missions_completed int(11) DEFAULT 0,
            total_time int(11) DEFAULT 0,
            average_time decimal(10,2) DEFAULT 0,
            ranking_score decimal(10,2) DEFAULT 0,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY year_month (year_month),
            KEY ranking_score (ranking_score),
            UNIQUE KEY unique_user_month (user_id, year_month)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($missions_sql);
        dbDelta($daily_missions_sql);
        dbDelta($user_progress_sql);
        dbDelta($rankings_sql);
    }
    
    /**
     * Drop plugin database tables (used on uninstall)
     */
    public static function drop_tables() {
        global $wpdb;
        
        $tables = array(
            $wpdb->prefix . 'ahoninmu_missions',
            $wpdb->prefix . 'ahoninmu_daily_missions',
            $wpdb->prefix . 'ahoninmu_user_progress',
            $wpdb->prefix . 'ahoninmu_rankings'
        );
        
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }
    }
}
