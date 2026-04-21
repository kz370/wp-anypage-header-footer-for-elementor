<?php
/**
 * Plugin Name: Force Elementor Template Override
 * Description: Forces custom templates to load for Elementor pages, bypassing default template loading.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

add_filter('template_include', function($template) {

    if (!is_page()) {
        return $template;
    }

    $slug = get_page_template_slug();

    // Only target templates starting with "template-cu-"
    if ($slug && str_starts_with($slug, 'template-cu-')) {

        $file = get_stylesheet_directory() . '/' . $slug;

        // Safety check: make sure file exists
        if (file_exists($file)) {
            return $file;
        }
    }

    return $template;

}, 9999);
