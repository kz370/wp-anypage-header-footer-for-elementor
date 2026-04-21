<?php
/**
 * Plugin Name: AnyPage Header Footer for Elementor
 * Plugin URI: https://example.com/
 * Description: Use any page or post as a header or footer template in Elementor.
 * Version: 1.0.0
 * Author: kz370
 * Author URI: https://example.com/
 * License: GPLv2 or later
 * Text Domain: anypage-header-footer-elementor
 */
    
// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

define('TEMPLATE_MANAGER_VERSION', '1.0.0');
define('TEMPLATE_MANAGER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TEMPLATE_MANAGER_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Activate the plugin
 */
function tm_activate() {
    if (!wp_next_scheduled('tm_plugin_activation')) {
        wp_schedule_single_event(time(), 'tm_plugin_activation');
    }
}
register_activation_hook(__FILE__, 'tm_activate');

/**
 * Deactivate the plugin
 */
function tm_deactivate() {
    wp_clear_scheduled_hook('tm_plugin_activation');
}
register_deactivation_hook(__FILE__, 'tm_deactivate');

// Include core classes
require_once TEMPLATE_MANAGER_PLUGIN_DIR . 'includes/class-tm-loader.php';
require_once TEMPLATE_MANAGER_PLUGIN_DIR . 'includes/class-tm-template-manager.php';
require_once TEMPLATE_MANAGER_PLUGIN_DIR . 'includes/functions.php';