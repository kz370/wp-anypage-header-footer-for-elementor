<?php

/**
 * Template Manager Class - Handles template creation, override, and management
 */

if (!defined('ABSPATH')) {
    exit;
}

class TM_Template_Manager
{

    /**
     * Initialize the template manager
     */
    public static function init()
    {
        add_filter('theme_page_templates', array(self::class, 'register_page_templates'), 20, 4);
        add_filter('theme_post_templates', array(self::class, 'register_page_templates'), 20, 4);
        add_filter('theme_templates', array(self::class, 'register_page_templates'), 20, 4);
        add_filter('template_include', array(self::class, 'force_template_override'), 9999);

        // Enqueue admin assets for template management
        add_action('admin_enqueue_scripts', array(self::class, 'enqueue_admin_assets'));

        if (current_user_can('edit_posts') || current_user_can('manage_options')) {
            add_action('admin_menu', array(self::class, 'add_template_management_menu'));
        }
    }

    /**
     * Register plugin templates so they appear in the page editor.
     */
    public static function register_page_templates($templates, $theme, $post, $post_type)
    {
        $available_templates = tm_get_registered_templates();

        foreach ($available_templates as $template) {
            $templates[$template['slug']] = $template['name'];
        }

        return $templates;
    }

    /**
     * Force template override filter
     */
    public static function force_template_override($template)
    {
        if (!is_singular()) {
            return $template;
        }

        $slug = get_page_template_slug();

        if (!$slug) {
            return $template;
        }

        $template_data = tm_get_template_file_by_slug($slug);

        if (!$template_data) {
            return $template;
        }

        // If it's a database template, we need to handle it specially
        if (is_array($template_data) && $template_data['source'] === 'database') {
            self::render_database_template($template_data);
            exit; // Stop further execution since we already rendered the content
        }

        // If it's a file path
        if (is_string($template_data) && file_exists($template_data)) {
            return $template_data;
        }

        return $template;
    }

    /**
     * Render a template stored in the database
     */
    public static function render_database_template($data)
    {
        $header_id = $data['header_id'];
        $footer_id = $data['footer_id'];
?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>

        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?php wp_head(); ?>
        </head>

        <body <?php body_class(); ?>>
            <?php wp_body_open(); ?>

            <?php
            // 🔝 Header (Elementor or WP Post/Page)
            if ($header_id) {
                if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::instance()->documents->get($header_id) && \Elementor\Plugin::instance()->documents->get($header_id)->is_built_with_elementor()) {
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($header_id);
                } else {
                    $header_post = get_post($header_id);
                    if ($header_post) {
                        echo apply_filters('the_content', $header_post->post_content);
                    }
                }
            }
            ?>

            <?php
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            ?>

            <?php
            // 🔻 Footer (Elementor or WP Post/Page)
            if ($footer_id) {
                if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::instance()->documents->get($footer_id) && \Elementor\Plugin::instance()->documents->get($footer_id)->is_built_with_elementor()) {
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($footer_id);
                } else {
                    $footer_post = get_post($footer_id);
                    if ($footer_post) {
                        echo apply_filters('the_content', $footer_post->post_content);
                    }
                }
            }
            ?>

            <?php wp_footer(); ?>
        </body>

        </html>
<?php
    }

    /**
     * Enqueue admin assets
     */
    public static function enqueue_admin_assets($hook)
    {
        // Load on any of our plugin pages (page param check is most reliable)
        $page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';
        $is_tm_page = in_array($page, array('tm-templates', 'tm-create-template', 'tm-template-list'), true)
            || in_array($hook, array('post-new.php', 'post.php'), true);

        if ($is_tm_page) {
            wp_enqueue_style(
                'tm-google-fonts',
                'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
                array(),
                null
            );
            wp_enqueue_style(
                'tm-admin-style',
                TEMPLATE_MANAGER_PLUGIN_URL . 'admin/css/admin.css',
                array('tm-google-fonts'),
                TEMPLATE_MANAGER_VERSION
            );
        }
    }

    /**
     * Add admin menu for template management
     */
    public static function add_template_management_menu()
    {
        add_menu_page(
            'Template Manager',
            'Templates',
            'manage_options',
            'tm-templates',
            array(self::class, 'render_admin_page'),
            'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"%3E%3Cpath fill="%232c3e50" d="M4 4.75A2.75 2.75 0 0 1 6.75 2h10.5A2.75 2.75 0 0 1 20 4.75v14.5A2.75 2.75 0 0 1 17.25 22H6.75A2.75 2.75 0 0 1 4 19.25V4.75Z"/%3E%3Cpath fill="%23ffffff" d="M7 7.25C7 6.56 7.56 6 8.25 6h7.5c.69 0 1.25.56 1.25 1.25v2.5c0 .69-.56 1.25-1.25 1.25h-7.5C7.56 11 7 10.44 7 9.75v-2.5Zm0 7C7 13.56 7.56 13 8.25 13h2.5c.69 0 1.25.56 1.25 1.25v2.5c0 .69-.56 1.25-1.25 1.25h-2.5C7.56 18 7 17.44 7 16.75v-2.5Zm6 0c0-.69.56-1.25 1.25-1.25h1.5c.69 0 1.25.56 1.25 1.25v2.5c0 .69-.56 1.25-1.25 1.25h-1.5c-.69 0-1.25-.56-1.25-1.25v-2.5Z"/%3E%3C/svg%3E',
            100
        );
    }

    /**
     * Render admin page
     */
    public static function render_admin_page()
    {
        include TEMPLATE_MANAGER_PLUGIN_DIR . 'admin/pages/create-template.php';
    }

    /**
     * Render create template page
     */
    public static function render_create_template()
    {
        wp_safe_redirect(admin_url('admin.php?page=tm-templates'));
        exit;
    }

    /**
     * Render template list page
     */
    public static function render_template_list()
    {
        wp_safe_redirect(admin_url('admin.php?page=tm-templates'));
        exit;
    }

    /**
     * Generate a custom template file for Elementor
     * 
     * @param string $name Template name (e.g., "New Layout 2026")
     * @param int|null $header_id Custom header Elementor ID
     * @param int|null $footer_id Custom footer Elementor ID
     * @return array Generated template data
     */
    public static function generate_elementor_template($name, $header_id = null, $footer_id = null)
    {
        $header_id = absint($header_id);
        $footer_id = absint($footer_id);
        $slug_base = sanitize_title($name);
        $slug = ($slug_base ?: 'custom-template');
        
        $main_file = 'template-cu-' . $slug . '.php';
        $header_file = 'header-' . $slug . '.php';
        $footer_file = 'footer-' . $slug . '.php';

        return [
            'name' => $name,
            'slug' => $slug,
            'files' => [
                $main_file => self::get_main_template_content($name, $slug),
                $header_file => self::get_header_content($header_id),
                $footer_file => self::get_footer_content($footer_id),
            ],
            'header_id' => $header_id,
            'footer_id' => $footer_id,
        ];
    }

    /**
     * Get template content with Elementor integration
     */
    /**
     * Get main template content
     */
    public static function get_main_template_content($name, $slug)
    {
        return <<<PHP
<?php
/* Template Name: {$name} */

get_header('{$slug}');

while ( have_posts() ) :
    the_post();
    the_content();
endwhile;

get_footer('{$slug}');
PHP;
    }

    /**
     * Get header file content
     */
    public static function get_header_content($header_id)
    {
        return <<<PHP
<?php
/**
 * Custom Header File
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
\$tm_has_custom_header = false;

if ({$header_id}) {
    \$tm_document = class_exists('\Elementor\Plugin') ? \Elementor\Plugin::instance()->documents->get({$header_id}) : null;

    if (\$tm_document && \$tm_document->is_built_with_elementor()) {
        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display({$header_id});
        \$tm_has_custom_header = true;
    } else {
        \$header_post = get_post({$header_id});
        if (\$header_post instanceof WP_Post) {
            echo apply_filters('the_content', \$header_post->post_content);
            \$tm_has_custom_header = true;
        }
    }
}

if (!\$tm_has_custom_header) {
    get_header();
}
?>
PHP;
    }

    /**
     * Get footer file content
     */
    public static function get_footer_content($footer_id)
    {
        return <<<PHP
<?php
/**
 * Custom Footer File
 */

\$tm_has_custom_footer = false;

if ({$footer_id}) {
    \$tm_document = class_exists('\Elementor\Plugin') ? \Elementor\Plugin::instance()->documents->get({$footer_id}) : null;

    if (\$tm_document && \$tm_document->is_built_with_elementor()) {
        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display({$footer_id});
        \$tm_has_custom_footer = true;
    } else {
        \$footer_post = get_post({$footer_id});
        if (\$footer_post instanceof WP_Post) {
            echo apply_filters('the_content', \$footer_post->post_content);
            \$tm_has_custom_footer = true;
        }
    }
}

if (!\$tm_has_custom_footer) {
    get_footer();
}
?>
<?php wp_footer(); ?>
</body>
</html>
PHP;
    }
}
