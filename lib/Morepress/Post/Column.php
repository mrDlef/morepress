<?php

namespace Morepress\Post;

class Column {

	protected $_name;
	protected $_title;

	public function __construct($name, $title)
	{
		$this->_name = $name;
		$this->_title = $title;
	}

	public function add() {
		add_filter('manage_posts_columns', array($this, 'wpManage'));
	}

	public function wpManage($columns)
	{
		return array_merge($columns, array($this->_name => $this->_title));
	}

	public function content($callback)
	{
		add_action('manage_posts_custom_column', $callback, 10, 2);
	}

}
