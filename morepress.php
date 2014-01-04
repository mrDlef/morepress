<?php
/*
  Plugin Name: Morepress
  Version: 1.0.0
  Plugin URI: https://github.com/daidais/morepress
  Description: Framework to do lots of shit easily
  Author: Denis Favreau
  Author URI: http://www.daidais.net
  License: MIT
 */

/* inject cpt archives meta box */
global $meta_box_nav_menu_post_type_archive;
$meta_box_nav_menu_post_type_archive = \Morepress\Meta_Box\Nav_Menu\Post_Type_Archive::forge('custom-post-type-archive', __( 'Custom post type Archives'), 'nav-menus', 'side');

add_action('admin_head-nav-menus.php', 'morepress_meta_box_nav_menu_post_type_archive_push');

function morepress_meta_box_nav_menu_post_type_archive_push()
{
	global $meta_box_nav_menu_post_type_archive;
	add_meta_box('custom-post-type-archive', __('Custom post type archives'), array($meta_box_nav_menu_post_type_archive, 'callback'), 'nav-menus', 'side', 'default');
}
/* take care of the urls */
add_filter('wp_get_nav_menu_items', array($meta_box_nav_menu_post_type_archive, 'menuFilter'), 10, 3);
