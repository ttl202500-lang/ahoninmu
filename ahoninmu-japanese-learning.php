<?php
/**
 * Plugin Name: Ahoninmu Japanese Learning
 * Plugin URI: https://github.com/ttl202500-lang/ahoninmu
 * Description: A complete WordPress plugin for learning Japanese with daily missions, rankings, and leaderboards.
 * Version: 1.0.0
 * Author: Ahoninmu Team
 * Author URI: https://github.com/ttl202500-lang/ahoninmu
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ahoninmu-japanese
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('AHONINMU_VERSION', '1.0.0');
define('AHONINMU_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AHONINMU_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AHONINMU_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once AHONINMU_PLUGIN_DIR . 'includes/class-ahoninmu-database.php';
require_once AHONINMU_PLUGIN_DIR . 'includes/class-ahoninmu-missions.php';
require_once AHONINMU_PLUGIN_DIR . 'includes/class-ahoninmu-leaderboard.php';
require_once AHONINMU_PLUGIN_DIR . 'includes/class-ahoninmu-shortcodes.php';
require_once AHONINMU_PLUGIN_DIR . 'includes/class-ahoninmu-admin.php';

/**
 * Main Plugin Class
 */
class Ahoninmu_Japanese_Learning {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Initialize plugin
        add_action('plugins_loaded', array($this, 'init'));
        
        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    public function init() {
        // Load text domain for translations
        load_plugin_textdomain('ahoninmu-japanese', false, dirname(AHONINMU_PLUGIN_BASENAME) . '/languages');
        
        // Initialize classes
        Ahoninmu_Missions::get_instance();
        Ahoninmu_Leaderboard::get_instance();
        Ahoninmu_Shortcodes::get_instance();
        
        if (is_admin()) {
            Ahoninmu_Admin::get_instance();
        }
    }
    
    public function activate() {
        // Create database tables
        Ahoninmu_Database::create_tables();
        
        // Schedule daily cron job for mission reset
        if (!wp_next_scheduled('ahoninmu_daily_reset')) {
            wp_schedule_event(strtotime('tomorrow midnight'), 'daily', 'ahoninmu_daily_reset');
        }
        
        // Add default missions if none exist
        Ahoninmu_Missions::add_default_missions();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        // Clear scheduled cron job
        $timestamp = wp_next_scheduled('ahoninmu_daily_reset');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'ahoninmu_daily_reset');
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    public function enqueue_frontend_assets() {
        // Enqueue CSS
        wp_enqueue_style(
            'ahoninmu-frontend',
            AHONINMU_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            AHONINMU_VERSION
        );
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'ahoninmu-frontend',
            AHONINMU_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            AHONINMU_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('ahoninmu-frontend', 'ahoninmu_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ahoninmu_nonce')
        ));
    }
    
    public function enqueue_admin_assets($hook) {
        // Only load on plugin admin pages
        if (strpos($hook, 'ahoninmu') === false) {
            return;
        }
        
        wp_enqueue_style(
            'ahoninmu-admin',
            AHONINMU_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            AHONINMU_VERSION
        );
        
        wp_enqueue_script(
            'ahoninmu-admin',
            AHONINMU_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            AHONINMU_VERSION,
            true
        );
    }
}

// Initialize the plugin
Ahoninmu_Japanese_Learning::get_instance();
