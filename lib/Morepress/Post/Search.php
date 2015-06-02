<?php

namespace Morepress\Post;

class Search {

	protected $_search_query = array();

	public function __construct() {
		add_filter('posts_search', array($this, 'query'), 500, 2);
	}

	public function query($search, &$wp_query) {
		global $wpdb;

		if (empty($search))
		{
			return $search;
		}

		$terms = $wp_query->query_vars['s'];
		$exploded = explode(' ', $terms);
		if ($exploded === false || count($exploded) == 0)
		{
			$exploded = array(0 => $terms);
		}

		$search = array();

		foreach($this->_search_query as $search_part)
		{
			foreach ($exploded as $term) {
				$search[] = $wpdb->prepare($search_part, '%' . $wpdb->esc_like( $term ) . '%');
			}

		}

		return ' AND ('.implode(' OR ', $search).')';
	}

	public function addFields($fields) {
		global $wpdb;
		is_string($fields) and $fields = array($fields);
		foreach($fields as $field)
		{
			$this->_search_query[] = '('.$wpdb->prefix.'posts.'.$field.' LIKE \'%s\')';
		}
	}

	public function addTaxonomies($taxonomies) {
		global $wpdb;
		is_string($taxonomies) and $taxonomies = array($taxonomies);
		foreach($taxonomies as $taxonomy)
		{
			$query = '('.$wpdb->prefix.'posts.ID IN (
				SELECT '.$wpdb->prefix.'posts.ID FROM '.$wpdb->prefix.'posts
				LEFT JOIN '.$wpdb->prefix.'term_relationships
					ON '.$wpdb->prefix.'term_relationships.object_id = '.$wpdb->prefix.'posts.ID
				LEFT JOIN '.$wpdb->prefix.'term_taxonomy
					ON '.$wpdb->prefix.'term_taxonomy.term_taxonomy_id = '.$wpdb->prefix.'term_relationships.term_taxonomy_id
				LEFT JOIN '.$wpdb->prefix.'terms
					ON '.$wpdb->prefix.'terms.term_id = '.$wpdb->prefix.'term_taxonomy.term_id
				WHERE '.$wpdb->prefix.'term_taxonomy.taxonomy = \''.$taxonomy.'\'
					AND '.$wpdb->prefix.'terms.name LIKE \'%s\'
			))';


			$this->_search_query[] = $query;
		}
	}

	public function addAuthorFields($fields) {
		global $wpdb;
		is_string($fields) and $fields = array($fields);
		foreach($fields as $field)
		{
			$query = '('.$wpdb->prefix.'posts.ID IN (
				SELECT '.$wpdb->prefix.'posts.ID FROM '.$wpdb->prefix.'posts
				LEFT JOIN '.$wpdb->prefix.'users
					ON '.$wpdb->prefix.'users.ID = '.$wpdb->prefix.'posts.post_author
				WHERE '.$wpdb->prefix.'users.'.$field.' LIKE \'%s\'
			))';


			$this->_search_query[] = $query;
		}
	}

	public function addAuthorMeta($keys) {
		global $wpdb;
		is_string($keys) and $keys = array($keys);
		foreach($keys as $key)
		{
			$query = '('.$wpdb->prefix.'posts.ID IN (
				SELECT '.$wpdb->prefix.'posts.ID FROM '.$wpdb->prefix.'posts
				LEFT JOIN '.$wpdb->prefix.'users
					ON '.$wpdb->prefix.'users.ID = '.$wpdb->prefix.'posts.post_author
				LEFT JOIN '.$wpdb->prefix.'usermeta
					ON '.$wpdb->prefix.'usermeta.user_id = '.$wpdb->prefix.'users.ID
				WHERE '.$wpdb->prefix.'usermeta.meta_key = \''.$key.'\'
					AND '.$wpdb->prefix.'usermeta.meta_value LIKE \'%s\'
			))';


			$this->_search_query[] = $query;
		}
	}

	public function wpRegister($method) {
		add_filter('posts_search', array($this, $method), 500, 2);
	}

}
