<?php

/*
  Plugin Name: Morepress
  Version: 0.9.7
  Plugin URI: https://github.com/daidais/morepress
  Description: Framework to do lots of things programmatically with pleasure
  Author: Denis Favreau
  Author URI: http://www.daidais.net
  License: MIT
 */
define('MOREPRESS_PLUGIN_FILE', __FILE__);
$autoload_file = dirname(__FILE__).'/../../../vendor/autoload.php';
if(file_exists($autoload_file)) {
    require_once($autoload_file);
}

add_action('plugins_loaded', function() {
	/* inject cpt archives meta box */
	global $meta_box_nav_menu_post_type_archive;
	$meta_box_nav_menu_post_type_archive = \Morepress\Meta_Box\Nav_Menu\Post_Type_Archive::forge('post-type-archive', __('Archives'), 'nav-menus', 'side');

	add_action('admin_head-nav-menus.php', function() {
		global $meta_box_nav_menu_post_type_archive;
		add_meta_box('post-type-archive', __('Archives'), array($meta_box_nav_menu_post_type_archive, 'callback'), 'nav-menus', 'side', 'default');
	});

	/* take care of the urls */
	add_filter('wp_get_nav_menu_items', array($meta_box_nav_menu_post_type_archive, 'menuFilter'), 10, 3);
});

add_action('admin_enqueue_scripts', function($hook) {
	$allowed_hooks = array(
		'post-new.php',
		'post.php',
		'edit-tags.php',
		'profile.php',
		'user-edit.php',
	);
	if (!in_array($hook, $allowed_hooks)) {
		return;
	}
	wp_register_script('morepress-js', plugins_url('/js/script.js', __FILE__), array('jquery'), null, true);
	$php_array = array('admin_ajax' => admin_url('admin-ajax.php'));
	wp_localize_script('morepress-js', 'php_array', $php_array);

    wp_enqueue_script('jquery-ui-autocomplete');
    wp_enqueue_script('morepress-js');
});

function morepress_get_term_image($term, $slug, $size = 'thumbnail', $icon = false) {
    if(! empty($term->term_id)) {
        $presenter_fields = get_option('taxonomy_term_' . $term->term_id);
        if (!empty($presenter_fields[$slug])) {
            return wp_get_attachment_image_src($presenter_fields[$slug], $size, $icon);
        }
	}
	return false;
}

function morepress_get_user_meta_image($user, $slug, $size = 'thumbnail', $icon = false) {
	if ($attachment_id = get_user_meta($user->ID, $slug, true)) {
		return wp_get_attachment_image_src($attachment_id, $size, $icon);
	}
	return false;
}

if ( ! function_exists('morepress_shortcode')) :
    function morepress_shortcode($name, $title, $callback = null, $fields = null) {
        \Morepress\Shortcode::forge($name, $title, $callback, $fields);
    }
endif;

\Morepress\Shortcode_Manager::forge();
