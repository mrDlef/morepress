<?php

namespace Morepress\Taxonomy;

abstract class Field
{
	protected $_taxonomy;
	protected $_type;
	protected $_slug;
	protected $_params = array();

	public function __construct($taxonomy, $slug, $params = array())
	{
		$this->_taxonomy = $taxonomy;
		$this->_slug = $slug;
		$this->_params = $this->_setDefaultParams($params);
		$this->_taxonomy->onEdit(array($this, 'callback'));
	}

	public function callback($term = null)
	{

	}

	protected function _setDefaultParams($params = array())
	{
		$default_params = array(
			'label' => ucfirst($this->_slug),
		);

		return array_merge($default_params, $params);
	}
}