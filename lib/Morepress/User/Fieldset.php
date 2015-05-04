<?php

namespace Morepress\User;

class Fieldset {

	protected $_name;

	protected $_title;

	protected $_fields = array();

	public function __construct($name, $title) {
		$this->_name = $name;
		$this->_title = $title;
		
		add_action('show_user_profile', array($this, 'render'));
		add_action('edit_user_profile', array($this, 'render'));
	}

	function addField($type, $slug, $params = array())
	{
		$class_name = 'Morepress\\User\\Field\\' . ucfirst($type);
		if (class_exists($class_name)) {
			$this->_fields[] = new $class_name($slug, $params);
			return true;
		}
		return false;
	}

	function render($user) {
		?>
		<h3><?php echo $this->_title; ?></h3>

		<table class="form-table">
			<?php foreach($this->_fields as $field) : ?>
				<?php $field->render($user); ?>
			<?php endforeach; ?>
		</table>
		<?php
	}

	public function save($user_id)
	{
		foreach($this->_fields as $field)
		{
			$field->save($user_id);
		}
	}

}
