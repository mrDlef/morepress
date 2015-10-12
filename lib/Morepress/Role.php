<?php

namespace Morepress;

class Role {

	protected static $_roles = array();
	protected $_name;
	protected $_role;

    public static function add($role, $display_name, $capabilities = array()) {
		add_role($role, $display_name, $capabilities);
		return new static($role);
	}

    public static function remove($role) {
		remove_role($role);
	}

    public static function forge($role) {
        if (empty(static::$_roles[$role])) {
			static::$_roles[$role] = new static($role);
		}
		return static::$_roles[$role];
	}

    protected function __construct($role) {
		$this->_name = $role;
		$this->_role = get_role($role);
	}

    public function get() {
		return $this->_role;
	}

    public function addCap($cap, $grant = true) {
        $this->_role->add_cap($cap, $grant);
	}

    public function removeCap($cap) {
        $this->_role->remove_cap($cap);
    }

    public function __get($key) {
        $this->_role->{$key};
	}

}
