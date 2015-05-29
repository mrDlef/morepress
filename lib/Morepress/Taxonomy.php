<?php

namespace Morepress;

class Taxonomy
{

	protected static $_taxonomies = array();
	protected $_taxonomy;
	protected $_object_type;
	protected $_args;
	protected $_onsave_registered = false;
	protected static $_to_register = array();
	protected static $_to_unregister = array();
	protected static $_on_init_registered = false;

	public static function forge($taxonomy, $object_type = null, $args = array())
	{
		if (isset(static::$_taxonomies[$taxonomy]))
		{
			return static::$_taxonomies[$taxonomy];
		}
		static::$_taxonomies[$taxonomy] = new static($taxonomy, $object_type, $args);
		return static::$_taxonomies[$taxonomy];
	}

	public static function exists($taxonomy)
	{
		return taxonomy_exists($taxonomy);
	}

	public static function find($args = array(), $output = 'names', $operator = 'and')
	{
		return get_taxonomies($args, $output, $operator);
	}

	public static function register($taxonomy, $object_type = null, $args = array())
	{
		static::$_to_register[$taxonomy] = array(
			'object_type' => $object_type,
			'args' => $args,
		);
		static::registerOnInit();
	}

	public static function unregister($taxonomy)
	{
		static::$_to_unregister[$taxonomy] = $taxonomy;
		static::registerOnInit();
	}

	public static function registerOnInit()
	{
		if(! static::$_on_init_registered)
		{
			add_action('init', array(__CLASS__, 'onInit'));
			static::$_on_init_registered = true;
		}
	}

	public static function onInit() {
		if(!empty(static::$_to_unregister))
		{
			foreach (static::$_to_unregister as $taxonomy)
			{
				global $wp_taxonomies;
				if (static::exists($taxonomy))
				{
					unset($wp_taxonomies[$taxonomy]);
				}
			}
		}
		if(!empty(static::$_to_register))
		{
			foreach(static::$_to_register as $taxonomy=>$option)
			{
				register_taxonomy($taxonomy, $option['object_type'], $option['args']);
			}
		}
	}

	protected function __construct($taxonomy, $object_type = null, $args = array())
	{
		$this->_taxonomy = $taxonomy;

		is_string($object_type) and $object_type = array($object_type);
		
		$this->_object_type = $object_type;

		$this->_args = $this->_setDefaultArgs($args);

		if (!static::exists($taxonomy))
		{
			add_action('init', array($this, 'wpRegister'));
		}
	}

	protected function _setDefaultArgs($args = array())
	{
		$default_args = array();
		
		$default_args['labels']['name'] = _x(ucfirst($this->_taxonomy).'s', 'Post Type General Name', 'text_domain');
		$default_args['labels']['singular_name'] = _x(ucfirst($this->_taxonomy), 'Post Type Singular Name', 'text_domain');

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
		
		$default_args['rewrite']['slug'] = $this->_taxonomy;

		return array_merge($default_args, $args);
	}

	public function getQuery($args = array())
	{
		$args['taxonomy'] = $this->_taxonomy;
		return new \WP_Query($args);
	}
	
	public function getName()
	{
		return $this->_taxonomy;
	}

	public function getObject()
	{
		return get_taxonomy_object($this->_taxonomy);
	}

	public function isHierarchical()
	{
		return is_taxonomy_hierarchical($this->_taxonomy);
	}

	public function isArchive()
	{
		return is_taxonomy_archive($this->_taxonomy);
	}

	public function wpRegister()
	{
		register_taxonomy($this->_taxonomy, $this->_object_type, $this->_args);
	}

	public function addField($type, $slug, $params = array())
	{
		$class_name = '\\Morepress\\Taxonomy\\Field\\'.ucfirst($type);
		if(class_exists($class_name))
		{
			return new $class_name($this, $slug, $params);
		}
	}

	public function onEdit($callback)
	{
		$this->registerOnSave();
		add_action( $this->_taxonomy.'_edit_form_fields', $callback, 10, 2 );
	}
	
	public function registerOnSave()
	{
		if(! $this->_onsave_registered)
		{
			add_action( 'edited_'.$this->_taxonomy, array($this, 'onSave'), 10, 2 );
			$this->_onsave_registered = true;
		}
	}
	
	public function onSave($term_id, $tt_id)
	{
		if (isset($_POST['term_meta'])) {
			$term_meta = get_option('taxonomy_term_'.$term_id);
			$cat_keys = array_keys($_POST['term_meta']);
			foreach ($cat_keys as $key) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
			//save the option array  
			update_option('taxonomy_term_'.$term_id, $term_meta);
		}
	}

}
