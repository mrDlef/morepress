<?php

namespace Morepress;

abstract class Meta_Box
{

	protected static $_meta_boxes = array();
	protected $_id;
	protected $_title;
	protected $_post_types = array();
	protected $_context;
	protected $_priority;

	public static function forge($id, $title = null, $post_types = null, $context = null, $priority = null)
	{
		if (isset(static::$_meta_boxes[$id]))
		{
			return static::$_meta_boxes[$id];
		}
		return new static($id, $title, $post_types, $context, $priority);
	}

	public function __construct($id, $title, $post_types, $context = null, $priority = null)
	{
		$this->_id = $id;
		$this->_title = $title;

		is_string($post_types) and $post_types = array($post_types);
		$this->_post_types = $post_types;

		$this->_context = $context;
		$this->_priority = $priority;
	}

	public function callback()
	{
		
	}

	public static function remove($page)
	{
		if (($key = array_search($page, $this->_post_types)) !== false)
		{
			unset($this->_post_types[$key]);
		}
		remove_meta_box($this->_id, $page, $this->_context);
	}

}
