<?php

namespace Morepress;

class User {

	protected static $_onsave_registered = false;
	protected static $_fieldsets = array();

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
