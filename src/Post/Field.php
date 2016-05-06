<?php

namespace Morepress\Post;

abstract class Field {

	protected $_prefix_id = '';
	/**
	 * @var string The field slug, used as name and ID in HTML markup
	 */
	protected $_slug;

	/**
	 * @var string the readable label, used for user experience
	 */
	protected $_label;

	/**
	 * @var string the description to help user to fill field
	 */
	protected $_description;

	/**
	 * @var array Complementary params
	 */
	protected $_params;

	/**
	 * @var string ID used in repeatable case
	 */
	protected $_id;

	/**
	 * @param $slug the slug wanted
	 * @param null $desc \Morepress\Post\Field description
	 * @param array $params Complementary params
	 */
	public function __construct($slug, $desc = null, $params = array()) {
		$this->_slug = $slug;
        if(is_array($desc)) {
            $this->_params = $desc;
            isset($this->_params['description']) and $this->_description = $this->_params['description'];
        }
        else {
            $this->_description = $desc;
            $this->_params = $params;
        }
        isset($this->_params['repeatable'])or $this->_params['repeatable'] = false;
		// If label provided, we use it. If not, use the slug.
		if (isset($this->_params['label'])) {
            $this->_label = $this->_params['label'];
        }
		else {
            $this->_label = $slug;
        }
		// Sanitize ID field
		$slug = strtolower(str_replace(array('\'', ' ', '"'), '_', $this->_slug));
		$this->_id = $this->_prefix_id . $slug;
		$this->_name = $this->_id;
	}

	protected function _inputAttr()
	{
		if(! empty($this->_params['input_attr']))
		{
			$attributes = array();
			foreach($this->_params['input_attr'] as $attr=>$value)
			{
				$attributes[] = $attr.'="'.$value.'"';
			}
			return implode(' ', $attributes);
		}

	}

	/**
	 * @param $meta
	 *
	 * @return string
	 */
	public function output($meta, $repeatable = null) {
		$this->_beforeOutput($meta);
		$html = $this->html($meta, $repeatable);
		return $html;
	}

	protected function _beforeOutput($meta)
	{

	}

	public function html($meta, $repeatable = null) {

	}

	/**
	 * Pre-save post delegate
	 * @param $post_id the post ID to be saved
	 * @param $new the field new value
	 * @param $old the field old value
	 *
	 * @return mixed the new value
	 */
	public function pre_save($post_id, $new, $old) {
		return $new;
	}

	/**
	 * Pre-delete post delegate
	 * @param $post_id the post ID to be deleted
	 * @param $old the field old value
	 *
	 * @return bool true if OK, false otherwise
	 */
	public function pre_delete($post_id, $old) {
		return true;
	}

	/**
	 * @return string \Morepress\Post\Field ID getter
	 */
	public function get_id() {
		return $this->_id;
	}

	/**
	 * @return string \Morepress\Post\Field slug getter
	 */
	public function get_slug() {
		return $this->_slug;
	}

	/**
	 * @return string \Morepress\Post\Field label getter
	 */
	public function get_label() {
		return $this->_label;
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

	/**
	 * Save logic for field
	 * @param $post_id the ost Id to be saved
	 */
	public function save($post_id) {
		$new = null;
		isset($_POST[$this->get_id()]) and $new = $_POST[$this->get_id()];
		// Special save for repeatable Fields

		if ($this->_params['repeatable'] or $this->_params['grouped_repeatable']) {
            $old = get_post_meta($post_id, $this->get_id());
			delete_post_meta($post_id, $this->get_id());
            if ($new !== null) {
                if(! empty($new)) {
                    foreach ($new as $n) {
                        // pre save allow special treatment for each field before saving
                        $n = $this->pre_save($post_id, $n, $old);
                        add_post_meta($post_id, $this->get_id(), $n);
                    }
                }
			}
		} else { // Classic fields
            $old = get_post_meta($post_id, $this->get_id(), true);
			if ($new !== null && $new != $old) {
				// pre save allow special treatment for each field before saving
				$new = $this->pre_save($post_id, $new, $old);
				update_post_meta($post_id, $this->get_id(), $new, $old);
			} elseif (empty($new) && $old) {
				$this->pre_delete($post_id, $old);
				delete_post_meta($post_id, $this->get_id(), $old);
			}
		}
	}

}
