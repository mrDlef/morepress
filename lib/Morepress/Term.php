<?php

namespace Morepress;

class Term {

    protected $_term;
    protected $_meta;
    protected $_taxonomy;

    public static function forge($term = null, $taxonomy = null) {
        (empty($term) and is_tax()) and $term = get_queried_object();
        return new static($term, $taxonomy);
    }

    public function __construct($term, $taxonomy = null) {
        is_numeric($term) and $term = get_term($term, $taxonomy);
        $this->_term = $term;
    }

    public function exists() {
        return (!empty($this->_term));
    }

    public function __get($name) {
        return $this->_term->{$name};
    }

    public function getMeta($key = null) {
        empty($this->_meta) and $this->_meta = stripslashes_deep(get_option('taxonomy_term_' . $this->_term->term_id));

        if (empty($key)) {
            return $this->_meta;
        }
        if (isset($this->_meta[$key])) {
            return $this->_meta[$key];
        }
    }

    public function getTaxonomy() {
        empty($this->_taxonomy) and $this->_taxonomy = \Morepress\Taxonomy::forge($this->_term->taxonomy);
        return $this->_taxonomy;
    }

}
