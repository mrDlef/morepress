<?php

namespace Morepress;

class Post {

	protected $_post;

	public function __construct($post)
	{
		is_int($post) and $post = get_post($post);
		$this->_post = $post;
	}

	public function addMeta($meta_key, $meta_value, $unique = false)
	{
		return add_post_meta($this->_post->ID, $meta_key, $meta_value, $unique);
	}

	public function updateMeta($meta_key, $meta_value, $prev_value = '')
	{
		return update_post_meta($this->_post->ID, $meta_key, $meta_value, $prev_value);
	}

	public function getMeta($key = '', $single = false)
	{
		return get_post_meta($this->_post->ID, $key, $single);
	}

	public function getTime($d = '')
	{
		return get_the_time($d, $this->_post);
	}

	public function deleteMeta($meta_key, $meta_value = '') {
		return delete_post_meta($this->_post->ID, $meta_key, $meta_value);
	}

	public function __get($name)
	{
		return $this->_post->{$name};
	}

}
