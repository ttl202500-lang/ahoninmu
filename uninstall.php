<?php
/**
 * Uninstall script
 * Fired when the plugin is uninstalled
 */

// Exit if accessed directly or not in uninstall context
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Load database class
require_once plugin_dir_path(__FILE__) . 'includes/class-ahoninmu-database.php';

// Delete database tables
Ahoninmu_Database::drop_tables();

// Delete plugin options
delete_option('ahoninmu_missions_per_day');
delete_option('ahoninmu_reset_time');

// Clear scheduled cron
$timestamp = wp_next_scheduled('ahoninmu_daily_reset');
if ($timestamp) {
    wp_unschedule_event($timestamp, 'ahoninmu_daily_reset');
}

// Clear any transients
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%ahoninmu%'");
