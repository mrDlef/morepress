<?php

namespace Morepress;

class Widget
{

	protected static $_widget_to_register = array();
	protected static $_widget_to_unregister = array();
	protected static $_on_widget_init_registered = false;

	public static function register($class)
	{
		static::$_widget_to_register[$class] = $class;
		static::registerOnWidgetInit();
	}

	public static function unregister($class)
	{
		static::$_widget_to_unregister[$class] = $class;
		static::registerOnWidgetInit();
	}

	public static function registerOnWidgetInit()
	{
		if(! static::$_on_widget_init_registered)
		{
			add_action('widgets_init', array(__CLASS__, 'onWidgetInit'), 10);
			static::$_on_widget_init_registered = true;
		}
	}

	public static function onWidgetInit() {
		if(!empty(static::$_widget_to_unregister))
		{
			foreach(static::$_widget_to_unregister as $class)
			{
				unregister_widget($class);
			}
		}
		if(!empty(static::$_widget_to_register))
		{
			foreach(static::$_widget_to_register as $class)
			{
				register_widget($class);
			}
		}
	}
}