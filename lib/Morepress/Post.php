<?php

namespace Morepress;

class Post {

	protected $_post;
	protected $_terms = array();

    public static function forge($post = null) {
        return new static($post);
    }

	public function __construct($post = null)
	{
        empty($post) and $post = get_post($post);
		is_numeric($post) and $post = get_post($post);
		$this->_post = $post;
	}

	public function exists()
	{
		return (! empty($this->_post));
	}

	public function update($postarr = array(), $wp_error = false)
	{
		$postarr['ID'] = $this->ID;
		wp_update_post($postarr, $wp_error);
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
        if(is_bool($key)) {
            $all_meta = array();
            $meta = $this->getMeta();
            foreach($meta as $k=>$v) {
                $all_meta[$k] = $this->getMeta($k, $key);
            }
            return $all_meta;
        }
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

	public function hasThumbnail()
    {
        return (bool) get_the_post_thumbnail($this->_post->ID);
	}

	public function getThumbnail($size = 'post-thumbnail', $icon = false)
    {
        return wp_get_attachment_image_src(get_post_thumbnail_id($this->_post->ID), $size, $icon);
	}

	public function getThumbnailURL($size = 'post-thumbnail', $icon = false)
    {
        $thumbnail = $this->getThumbnail($size, $icon);
        if(! empty($thumbnail)) {
            return $thumbnail[0];
        }
	}

    public function getFirstTerm($taxonomy)
    {
        empty($this->_terms[$taxonomy]) and $this->_terms[$taxonomy] = get_the_terms(get_the_ID(), $taxonomy);
        if(! empty($this->_terms[$taxonomy])) {
            return \Morepress\Term::forge($this->_terms[$taxonomy][0]);
        }
    }

}
