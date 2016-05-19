<?php

namespace Morepress;

abstract class Meta_Box
{

	protected static $_meta_boxes = array();
	protected static $_prefix = 'metabox_';
	protected $_id;
	protected $_title;
	protected $_screens = array();
	protected $_context;
	protected $_priority;

	public static function forge($id, $title = null, $screens = null, $context = 'advanced', $priority = 'default')
	{
		if (isset(static::$_meta_boxes[$id]))
		{
			return static::$_meta_boxes[$id];
		}
		return new static($id, $title, $screens, $context, $priority);
	}

	public function __construct($id, $title, $screens = null, $context = 'advanced', $priority = 'default')
	{
		$this->_id = static::$_prefix.$id;
		$this->_title = $title;

		is_string($screens) and $screens = array($screens);
		$this->_screens = $screens;

		$this->_context = $context;
		$this->_priority = $priority;
		add_action( 'add_meta_boxes', array($this, 'add') );
	}

	public function callback($params)
	{

	}

	public function add()
	{
		foreach($this->_screens as $screen)
		{
			add_meta_box($this->_id, $this->_title, array($this, 'callback'), $screen, $this->_context);
		}
	}

	public static function remove($page)
	{
		if (($key = array_search($page, $this->_screens)) !== false)
		{
			unset($this->_screens[$key]);
		}
		remove_meta_box($this->_id, $page, $this->_context);
	}

}
