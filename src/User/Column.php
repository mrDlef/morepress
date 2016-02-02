<?php

namespace Morepress\User;

class Column {

	protected $_name;
	protected $_title;

	public function __construct($name, $title)
	{
		$this->_name = $name;
		$this->_title = $title;
		add_filter('manage_users_columns', array($this, 'wpManage'));
	}

	public function wpManage($columns)
	{
		return array_merge($columns, array($this->_name => $this->_title));
	}

	public function content($callback)
	{
		add_action('manage_users_custom_column', $callback, 10, 3);
	}

}
