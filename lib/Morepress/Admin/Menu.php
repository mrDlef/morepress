<?php

namespace Morepress\Admin;

class Menu
{
	protected static $_onadminmenu_registered = false;
	protected static $_menu_to_add = array();
	protected static $_menu_to_remove = array();
	protected static $_submenu_to_add = array();
	protected static $_submenu_to_remove = array();

	protected static function _register_on_admin_menu()
	{
		if(! static::$_onadminmenu_registered)
		{
			add_action('admin_menu', array(__CLASS__, 'onAdminMenu'));
			static::$_onadminmenu_registered = true;
		}
	}

	public static function add($page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null)
	{
		static::_register_on_admin_menu();
		static::$_menu_to_add[] = array(
			'page_title' => $page_title,
			'menu_title' => $menu_title,
			'capability' => $capability,
			'menu_slug' => $menu_slug,
			'function' => $function,
			'icon_url' => $icon_url,
			'position' => $position,
		);
	}

	public static function remove($menu_slug)
	{
		static::_register_on_admin_menu();
		static::$_menu_to_remove[] = array(
			'menu_slug' => $menu_slug,
		);
	}


	public static function addSub($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '')
	{
		static::_register_on_admin_menu();
		static::$_submenu_to_add[] = array(
			'parent_slug' => $parent_slug,
			'page_title' => $page_title,
			'menu_title' => $menu_title,
			'capability' => $capability,
			'menu_slug' => $menu_slug,
			'function' => $function,
		);
	}

	public static function removeSub($menu_slug, $submenu_slug)
	{
		static::_register_on_admin_menu();
		static::$_submenu_to_remove[] = array(
			'menu_slug' => $menu_slug,
			'submenu_slug' => $submenu_slug,
		);
	}

	public static function onAdminMenu()
	{
		if(! empty(static::$_menu_to_add))
		{
			foreach(static::$_menu_to_add as $item)
			{
				add_menu_page($item['page_title'], $item['menu_title'], $item['capability'], $item['menu_slug'], $item['function'], $item['icon_url'], $item['position']);
			}
		}

		if(! empty(static::$_menu_to_remove))
		{
			foreach(static::$_menu_to_remove as $item)
			{
				remove_menu_page($item['menu_slug']);
			}
		}

		if(! empty(static::$_submenu_to_add))
		{
			foreach(static::$_submenu_to_add as $item)
			{
				add_submenu_page($item['parent_slug'], $item['page_title'], $item['menu_title'], $item['capability'], $item['menu_slug'], $item['function']);
			}
		}

		if(! empty(static::$_submenu_to_remove))
		{
			foreach(static::$_submenu_to_remove as $item)
			{
				remove_submenu_page($item['menu_slug'], $item['submenu_slug']);
			}
		}
	}

}

