<?php
/* Template Name: New Layout 2026 */

// 🔧 Change these IDs easily
$custom_header_id = 7823;
$custom_footer_id = 7825;
$has_custom_header = false;
$has_custom_footer = false;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
// 🔝 Custom Header
if ($custom_header_id) {
    $header_document = class_exists('\Elementor\Plugin') ? \Elementor\Plugin::instance()->documents->get($custom_header_id) : null;

    if ($header_document && $header_document->is_built_with_elementor()) {
        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($custom_header_id);
        $has_custom_header = true;
    } else {
        $header_post = get_post($custom_header_id);
        if ($header_post instanceof WP_Post) {
            echo apply_filters('the_content', $header_post->post_content);
            $has_custom_header = true;
        }
    }
}

if (!$has_custom_header) {
    get_header();
}
?>

<?php
while ( have_posts() ) :
    the_post();
    the_content();
endwhile;
?>

<?php
// 🔻 Custom Footer
if ($custom_footer_id) {
    $footer_document = class_exists('\Elementor\Plugin') ? \Elementor\Plugin::instance()->documents->get($custom_footer_id) : null;

    if ($footer_document && $footer_document->is_built_with_elementor()) {
        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($custom_footer_id);
        $has_custom_footer = true;
    } else {
        $footer_post = get_post($custom_footer_id);
        if ($footer_post instanceof WP_Post) {
            echo apply_filters('the_content', $footer_post->post_content);
            $has_custom_footer = true;
        }
    }
}

if (!$has_custom_footer) {
    get_footer();
}
?>

<?php wp_footer(); ?>
</body>
</html>
