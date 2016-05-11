<?php

namespace Morepress\Post;

class Fieldset {

	protected $_name;
	protected $_legend;
	protected $_fields;
	protected $_repeatable;

	public function __construct($name, $legend, $repeatable = false) {
		$this->_name = $name;
		$this->_legend = $legend;
		$this->_repeatable = $repeatable;
	}

	public function addField($type, $slug, $desc = null, $params = array()) {
		$class_name = 'Morepress\\Post\\Field\\' . ucfirst($type);
		if (class_exists($class_name)) {
            if(is_array($desc)) {
                $desc = array_merge($desc, array(
                    'grouped_repeatable' => $this->_repeatable,
                ));
            }
            else {
                $params = array_merge($params, array(
                    'grouped_repeatable' => $this->_repeatable,
                ));
            }


			$this->_fields[] = new $class_name($slug, $desc, $params);
			return $this;
		}
		throw new \Exception('Field type doesn\'t exists');
	}

	public function isRepeatable() {
		return $this->_repeatable;
	}

	public function getLegend() {
		return $this->_legend;
	}

	public function getFields() {
		return $this->_fields;
	}

	public function nbFields() {
		return count($this->_fields);
	}

	public function getName() {
		return $this->_name;
	}

}
