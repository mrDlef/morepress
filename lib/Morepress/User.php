<?php

namespace Morepress;

class User {

	protected static $_onsave_registered = false;
	protected static $_fieldsets = array();

    protected $_user;

    public static function forge($user) {
        return new static($user);
    }

    public function __construct($user) {
        is_numeric($user) and $user = get_user_by('ID', $user);
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

}
