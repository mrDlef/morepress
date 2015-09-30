<?php

namespace Morepress;

class User {

	protected static $_onsave_registered = false;
	protected static $_fieldsets = array();

    protected $_user;

    public static function forge($user = null, $cache = true) {
        global $wpdb;
        empty($user) and $user = get_current_user_id();

        if (!$cache and is_numeric($user)) {
            if (!$user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->users WHERE ID = %s", $user))) {
                return false;
            }
            update_user_caches($user);
        }

        if (empty($user)) {
            return false;
        }
        return new static($user);
    }

    public function __construct($user) {
        is_numeric($user) and $user = get_user_by('id', $user);
        $this->_user = $user;
    }

    public function update($data) {
        $data = array_merge($data, array('ID' => $this->_user->ID));
        return wp_update_user($data);
    }

    public static function fetch($args = array()) {
        $users = array();
        $q_user = new \WP_User_Query($args);
        foreach($q_user->get_results() as $user) {
            $users[$user->ID] = new static($user);
        }
        return $users;
    }
	public static function addFieldset($name, $title)
	{
		if (isset(static::$_fieldsets[$name])) {
			return static::$_fieldsets[$name];
		}
		static::$_fieldsets[$name] = new \Morepress\User\Fieldset($name, $title);

		if (! static::$_onsave_registered)
		{
			add_action('personal_options_update', array(__CLASS__, 'onSave'));
			add_action('edit_user_profile_update', array(__CLASS__, 'onSave'));
			static::$_onsave_registered = true;
		}

		return static::$_fieldsets[$name];
	}

	public static function onSave($user_id)
	{
		foreach (static::$_fieldsets as $fieldset)
		{
			$fieldset->save($user_id);
		}
	}

	public function __get($name)
	{
		return $this->_user->{$name};
	}

	public function getWpObject()
	{
		return $this->_user;
	}

    public function getMeta($key = '', $single = false) {
        return get_user_meta($this->_user->ID, $key, $single);
	}

    public function addMeta($key, $value, $single = false) {
        return add_user_meta($this->_user->ID, $key, $value, $single);
	}

    public function updateMeta($key, $value, $prev_value = '') {
        return update_user_meta($this->_user->ID, $key, $value, $prev_value);
	}

}
