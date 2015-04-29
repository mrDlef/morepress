<?php

namespace Morepress;

class Post_Type
{

	protected static $_post_types = array();
	protected $_post_type;
	protected $_args;
	protected $_actions;
	protected $_add_support = array();
	protected $_remove_support = array();

	public static function forge($post_type, $args = array())
	{
		if (isset(static::$_post_types[$post_type]))
		{
			return static::$_post_types[$post_type];
		}
		static::$_post_types[$post_type] = new static($post_type, $args);
		return static::$_post_types[$post_type];
	}

	public static function exists($post_type)
	{
		return post_type_exists($post_type);
	}

	public static function find($args = array(), $output = 'names', $operator = 'and')
	{
		return get_post_types($args, $output, $operator);
	}

	protected function __construct($post_type, $args = array())
	{
		$this->_post_type = $post_type;

		$this->_args = $this->_setDefaultArgs($args);

		if (!static::exists($post_type))
		{
			add_action('init', array($this, 'wpRegister'));
		}
	}

	protected function _setDefaultArgs($args = array())
	{
		$default_args = array();
		
		$default_args['labels']['name'] = _x(ucfirst($this->_post_type).'s', 'Post Type General Name', 'text_domain');
		$default_args['labels']['singular_name'] = _x(ucfirst($this->_post_type), 'Post Type Singular Name', 'text_domain');

		$default_args['labels']['menu_name'] = __($default_args['labels']['name'], 'text_domain');
		$default_args['labels']['parent_item_colon'] = __('Parent '.$default_args['labels']['singular_name'].':', 'text_domain');
		$default_args['labels']['all_items'] = __('All '.$default_args['labels']['name'], 'text_domain');
		$default_args['labels']['view_item'] = __('View '.$default_args['labels']['singular_name'], 'text_domain');
		$default_args['labels']['add_new_item'] = __('Add New '.$default_args['labels']['singular_name'], 'text_domain');
		$default_args['labels']['add_new'] = __('New '.$default_args['labels']['singular_name'], 'text_domain');
		$default_args['labels']['edit_item'] = __('Edit '.$default_args['labels']['singular_name'], 'text_domain');
		$default_args['labels']['update_item'] = __('Update '.$default_args['labels']['singular_name'], 'text_domain');
		$default_args['labels']['search_items'] = __('Search '.$default_args['labels']['name'], 'text_domain');
		$default_args['labels']['not_found'] = __('No '.strtolower($default_args['labels']['name']).' found', 'text_domain');
		$default_args['labels']['not_found_in_trash'] = __('No '.strtolower($default_args['labels']['name']).' found in Trash', 'text_domain');
		
		$default_args['rewrite']['slug'] = $this->_post_type;
		
		if(isset($args['has_archive']) and $args['has_archive'] == true)
		{
			$default_args['show_in_nav_menus'] = true;
		}

		if(isset($args['hierarchical']) and $args['hierarchical'] == true)
		{
			$default_args['menu_icon'] = 'dashicons-admin-page';
		}

		return array_merge($default_args, $args);
	}

	public function getQuery($args = array())
	{
		$args['post_type'] = $this->_post_type;
		return new \WP_Query($args);
	}

	public function getName()
	{
		return $this->_post_type;
	}
	
	public function addSupport($support)
	{
		is_object($support) and $support = $support->getName();
		is_string($support) and $support = array($support);
		foreach($support as &$item)
		{
			is_object($item) and $item = $item->getName();
		}
		$this->_add_support = array_merge($this->_add_support, $support);

		add_action('init', array($this, 'wpAddSupport'));
	}

	public function removeSupport($support)
	{
		is_string($support) and $support = array($support);
		$this->_remove_support = array_merge($this->_remove_support, $support);
		add_action($this, 'wpRemoveSupport');
	}

	public function hasSupport($support)
	{
		return post_type_supports($this->_post_type, $support);
	}

	public function getObject()
	{
		return get_post_type_object($this->_post_type);
	}

	public function isHierarchical()
	{
		return is_post_type_hierarchical($this->_post_type);
	}

	public function isArchive()
	{
		return is_post_type_archive($this->_post_type);
	}

	public function wpRegister()
	{
		register_post_type($this->_post_type, $this->_args);
	}

	public function wpAddSupport()
	{
		add_post_type_support($this->_post_type, $this->_add_support);
	}

	public function wpRemoveSupport()
	{
		remove_post_type_support($this->_post_type, $this->_remove_support);
	}

	public function addAction($name, $callback) {
		$this->_action = array(
			 'name' => $name,
			 'callback' => $callback,
		);
		add_action($this->_action['name'], array($this, 'actionCallback'));
	}
	
	public function actionCallback()
	{
		global $post;
		if (get_post_type($post) == $this->_post_type) {
			$this->_action['callback']();
		}
	}
	
}
