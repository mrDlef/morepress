<?php

namespace Morepress\User\Field;

class Select extends \Morepress\User\Field
{

	public function render($user) {
		echo '<tr>';
		echo '<th><label for="' . $this->_id . '">' . $this->_params['label'] . '</label></th>';
		echo '
			<td>
				<select name="' . $this->_name . '" id="' . $this->_id . '" value="'.$this->_value($user).'" '.$this->_inputAttr().'>
        ';
        foreach($this->_params['options'] as $key => $value) {
            echo '<option'.($this->_value($user) == $key ? ' selected' : '').' value="'.$key.'">'.$value.'</option>';
        }
        echo '
                </select>
				' . $this->_description() . '
			</td>
		';
		echo '</tr>';
	}

	/**
	 * @param $user_id
	 */
	public function save($user_id) {
		update_user_meta($user_id, $this->_name, sanitize_text_field($_POST[$this->_name]));
	}
}