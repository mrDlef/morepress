<?php

namespace Morepress\Meta_Box;

class Field extends \Morepress\Meta_Box
{
	protected $_fields = array();
	
	protected $_fieldsets = array();
	
	public function __construct($id, $title, $screens = null, $context = 'advanced', $priority = 'default') {
		parent::__construct($id, $title, $screens, $context, $priority);
		add_action('save_post', array($this, 'saveData'));
	}
	
	public function addField($type, $slug, $desc = null, $params = null) {
		$class_name = 'Morepress\\Field\\' . ucfirst($type);
		if (class_exists($class_name))
		{
			$this->_fields[$slug] = new $class_name($slug, $desc, $params);
			return true;
		}
		return false;
	}
	
	public function addFieldset($name, $legend, $repeatable = false) {
		$this->_fieldsets[$name] = new \Morepress\Fieldset($name, $legend, $repeatable);
		return $this->_fieldsets[$name];
	}
	
	public function render($fields, $post = null, $index = null)
	{
		if (!empty($fields)) {
			echo '<table class="form-table">';
			foreach ($fields as $field) {
				$meta = null;
				if($post)
				{
					if(isset($index))
					{
						$meta = get_post_meta($post->ID, $field->get_id());
						if(! empty($meta) and is_array($meta))
						{
							if(isset($meta[$index]))
							{
								$meta = $meta[$index];
							}
							else {
								$meta = null;
							}
						}
					}
					else
					{
						$meta = get_post_meta($post->ID, $field->get_id(), true);
					}
				}

				echo $field->output($meta);
			}
			echo '</table>';
		}
	}
	
	public function renderFieldset($fieldset, $post)
	{
		if($fieldset->isRepeatable())
		{
			echo '<script type="text/template" id="tpl-fieldset-'.$fieldset->getName().'">';
			echo '<fieldset>';
			echo '<p><strong>'.$fieldset->getLegend().'</strong> <a class="group-repeatable-remove button button-small right" href="#">Supprimer</a></legend>';
			echo '<hr>';
			$this->render($fieldset->getFields());
			echo '<hr>';
			echo '</fieldset>';
			echo '</script>';
			if($post)
			{
				$i = 0;
				$fields = $fieldset->getFields();
				$first_field = $fields[key($fields)];
				$meta = get_post_meta($post->ID, $first_field->get_id());
				foreach($meta as $key=>$row)
				{
					echo '<fieldset>';
					echo '<p><strong>'.$fieldset->getLegend().'</strong> <a class="group-repeatable-remove button button-small right" href="#">Supprimer</a></legend>';
					echo '<hr>';
					$this->render($fieldset->getFields(), $post, $i);
					echo '<hr>';
					echo '</fieldset><br>';
					$i++;
				}
			}
			echo '<p><a class="group-repeatable-add button button-small" href="#tpl-fieldset-'.$fieldset->getName().'">Ajouter un élément</a></p>';
		}
		else
		{
			echo '<fieldset>';
			echo '<p><strong>'.$fieldset->getLegend().'</strong></legend>';
			echo '<hr>';
			$this->render($fieldset->getFields(), $post);
			echo '<hr>';
			echo '</fieldset>';
		}
	}
	
	public function callback($post) {
		echo '<input type="hidden" name="custom_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '" />';
		$this->render($this->_fields, $post);
		if (! empty($this->_fieldsets)) {
			echo '<fieldset>';
			foreach ($this->_fieldsets as $fieldset) {
				$this->renderFieldset($fieldset, $post);
			}
			echo '</fieldset>';
		}
	}
	
	public function saveData($post_id) {
		if (!isset($_POST['custom_meta_box_nonce'])) {
			return $post_id;
		}
		// verify nonce
		if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
			return $post_id;
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;
		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id))
				return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
		$this->_beforeSave($post_id);
		foreach ($this->_fields as $field) {
			if (isset($_POST[$field->get_id()])) {
				$field->save($post_id);
			}
		}
		foreach ($this->_fieldsets as $fieldset) {
			foreach ($fieldset->getFields() as $field) {
				if (isset($_POST[$field->get_id()])) {
					$field->save($post_id);
				}
			}
		}
		$this->_afterSave($post_id);
		return $post_id;
	}

	protected function _beforeSave($post_id)
	{

	}

	protected function _afterSave($post_id)
	{
		
	}

}
