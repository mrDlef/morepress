<?php

namespace Morepress\User\Field;

class Number extends \Morepress\User\Field
{

	public function render($user) {
		echo '<tr>';
		echo '<th><label for="' . $this->_id . '">' . $this->_params['label'] . '</label></th>';
		echo '
			<td>
				<input type="number" name="' . $this->_name . '" id="' . $this->_id . '" value="'.$this->_value($user).'" class="regular-text" '.$this->_inputAttr().'>
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
