<?php
/*
  Plugin Name: Morepress
  Version: 1.0.0
  Plugin URI: https://github.com/daidais/morepress
  Description: Framework to do lots of things programmatically with pleasure
  Author: Denis Favreau
  Author URI: http://www.daidais.net
  License: MIT
 */
add_action( 'plugins_loaded', function() {
/* inject cpt archives meta box */
global $meta_box_nav_menu_post_type_archive;
$meta_box_nav_menu_post_type_archive = \Morepress\Meta_Box\Nav_Menu\Post_Type_Archive::forge('post-type-archive', __( 'Post type archives'), 'nav-menus', 'side');

add_action('admin_head-nav-menus.php', 'morepress_meta_box_nav_menu_post_type_archive_push');

function morepress_meta_box_nav_menu_post_type_archive_push()
{
	global $meta_box_nav_menu_post_type_archive;
	add_meta_box('post-type-archive', __('Post type archives'), array($meta_box_nav_menu_post_type_archive, 'callback'), 'nav-menus', 'side', 'default');
}
/* take care of the urls */
add_filter('wp_get_nav_menu_items', array($meta_box_nav_menu_post_type_archive, 'menuFilter'), 10, 3);
});

function morepress_register_js($hook) {
	if ('post-new.php' != $hook and 'post.php' != $hook and 'edit-tags.php' != $hook)
	{
		return;
	}
	wp_register_script('morepress-js', plugins_url('/js/script.js', __FILE__), array('jquery'), null, true);
	$php_array = array('admin_ajax' => admin_url('admin-ajax.php'));
	wp_localize_script('morepress-js', 'php_array', $php_array);
	wp_enqueue_script('morepress-js');
}

add_action( 'admin_enqueue_scripts', 'morepress_register_js' );

function morepress_get_term_image($term, $size = 'thumbnail', $icon = false) {
	$presenter_custom_fields = get_option( 'taxonomy_term_'.$term->term_id );
	if(!empty($presenter_custom_fields['image'])) {
		return wp_get_attachment_image_src($presenter_custom_fields['image'], $size, $icon);
	}
	return false;
}
