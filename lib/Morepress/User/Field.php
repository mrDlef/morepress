<?php

namespace Morepress\User;

abstract class Field {

	protected $_prefix_id = '';
	/**
	 * @var string The field slug, used as name and ID in HTML markup
	 */
	protected $_slug;

	/**
	 * @var array Complementary params
	 */
	protected $_params;

	/**
	 * @var string ID
	 */
	protected $_id;

	/**
	 * @param $slug the slug wanted
	 * @param null $desc Field description
	 * @param array $params Complementary params
	 */
	public function __construct($slug, $params = array()) {
		$this->_slug = strtolower(str_replace(array('\'', ' ', '"'), '_', $slug));
		$this->_id = $this->_prefix_id . $this->_slug;
		$this->_name = $this->_id;
		$this->_params = $this->_setDefaultParams($params);

	}

	protected function _setDefaultParams($params = array())
	{
		$default_params = array(
			'label' => ucfirst($this->_slug),
			'description' => '',
		);

		return array_merge($default_params, $params);
	}

	/**
	 * @param $user
	 */
	public function render($user) {

	}

	/**
	 * @param $user_id
	 */
	public function save($user_id) {

	}

	/**
	 * @return string Field ID getter
	 */
	public function get_id() {
		return $this->_id;
	}

	/**
	 * @return string Field slug getter
	 */
	public function get_slug() {
		return $this->_slug;
	}

	/**
	 * Complementary params getter
	 * @param string $param any wanted param*
	 * @return array|bool Param if supplied and found, false otherwise
	 */
	public function get_params($param = null) {
		if (!$param) {
			return $this->_params;
		} else {
			if (isset($this->_params[$param])) {
				return $this->_params[$param];
			}
		}
		return false;
	}

}
