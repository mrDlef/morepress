<?php

namespace Morepress\Post;

class Status
{

	protected static $_statuses = array();
	protected static $_on_init_registered = false;

	public static function register($name, $args = array())
	{
		static::$_statuses[$name] = $args;
		if (!static::$_on_init_registered) {
			add_action('init', array(__CLASS__, 'onInit'));
			static::$_on_init_registered = true;
		}
	}

	public static function onInit()
	{
		foreach (static::$_statuses as $name => $args) {
			register_post_status($name, $args);
		}
	}

}
