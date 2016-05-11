<?php

namespace Morepress;

class Term {

    protected $_term;
    protected $_taxonomy;

    public static function forge($term = null, $taxonomy = null) {
        if(is_tax($taxonomy) or is_category() or is_tag()) {
            empty($term) and $term = get_queried_object();
        }
        return new static($term, $taxonomy);
    }

    public function __construct($term, $taxonomy) {
        is_numeric($term) and $term = get_term($term, $taxonomy);
        $this->_term = $term;
    }

    public function exists() {
        return (!empty($this->_term));
    }

    public function __get($name) {
        return $this->_term->{$name};
    }

    public function __isset($name) {
        if(isset($this->_term->{$name})) {
            return true;
        }
        return isset($this->{$name});
    }

    public static function fetch($taxonomies, $args = '') {
        $result = get_terms($taxonomies, $args);
        $terms = array();
        foreach($result as $result) {
            $terms[] = static::forge($result, $result->taxonomy);
        }
        return $terms;
    }

	public function addMeta($meta_key, $meta_value, $unique = false)
	{
		return add_term_meta($this->_term->term_id, $meta_key, $meta_value, $unique);
	}

	public function updateMeta($meta_key, $meta_value, $prev_value = '')
	{
		return update_term_meta($this->_term->term_id, $meta_key, $meta_value, $prev_value);
	}

	public function meta($key = '', $single = false)
	{
        echo $this->getMeta($key, $single);
	}

    public function getMeta($key = null, $single = false) {
        $value = null;
        if(function_exists('get_term_meta')) {
            $value = get_term_meta($this->_term->term_id, $key, $single);
        }
        if(empty($value)) {
            empty($this->_meta) and $this->_meta = stripslashes_deep(get_option('taxonomy_term_' . $this->_term->term_id));

            if (empty($key)) {
                $value = $this->_meta;
            }
            if (isset($this->_meta[$key])) {
                $value = $this->_meta[$key];
            }
        }
        return $value;
    }

	public function deleteMeta($meta_key, $meta_value = '') {
		return delete_term_meta($this->_term->term_id, $meta_key, $meta_value);
	}

    public function getTaxonomy() {
        empty($this->_taxonomy) and $this->_taxonomy = \Morepress\Taxonomy::forge($this->_term->taxonomy);
        return $this->_taxonomy;
    }

    public function getLink() {
        return get_term_link($this->_term, $this->_taxonomy);
    }

    public function getChildren() {
        $children = array();
        foreach(get_term_children($this->term_id, $this->taxonomy) as $term_id) {
            $children[] = static::forge($term_id, $this->taxonomy);
        }
        return $children;
    }

}
