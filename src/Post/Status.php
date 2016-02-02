<?php

namespace Morepress\Post;

class Status {

	protected static $_statuses = array();
	protected static $_on_init_registered = false;
	protected static $_on_admin_footer_hook = array(
		'post.php' => false,
	);
	protected static $_on_display_post_states = false;

	public static function register($name, $post_types = array(), $args = array()) {
		is_string($post_types) and $post_types = array($post_types);
		static::$_statuses[$name]['post_types'] = $post_types;
		static::$_statuses[$name]['args'] = $args;
		if (!static::$_on_init_registered) {
			add_action('init', array(__CLASS__, 'onInit'));
			static::$_on_init_registered = true;
		}
		if (!static::$_on_admin_footer_hook['post.php']) {
			add_action('admin_footer-post.php', array(__CLASS__, 'onAdminFooterHookPostPhp'));
			static::$_on_admin_footer_hook['post.php'] = true;
		}
		if (!static::$_on_display_post_states) {
			add_filter('display_post_states', array(__CLASS__, 'onDisplayPostStates'));
			static::$_on_display_post_states = true;
		}
	}

	public static function onInit() {
		foreach (static::$_statuses as $name => $option) {
			register_post_status($name, $option['args']);
		}
	}

	public static function onAdminFooterHookPostPhp() {
		global $post;
		echo '<script type="text/javascript">jQuery(document).ready(function($){';
		foreach (static::$_statuses as $name => $options) {
			if(in_array($post->post_type, $options['post_types'])) {
				$complete = '';
				$label = '';
				if ($post->post_status == $name) {
					$complete = ' selected=\"selected\"';
					$label = '<span id=\"post-status-display\"> ' . $options['args']['label'] . '</span>';
				}
				echo '
					$("#post_status").append("<option value=\"'.$name.'\" ' . $complete . '>' . $options['args']['label'] . '</option>");
					$(".misc-pub-section label").append("' . $label . '");
				';
			}
		}
		echo '});</script>';
	}

	public static function onDisplayPostStates($states) {
		global $post;
		$arg = get_query_var('post_status');
		foreach (static::$_statuses as $name => $option) {
			if ($arg != $name) {
				if ($post->post_status == $name) {
					return array($option['args']['label']);
				}
			}
		}
		return $states;
	}

}
