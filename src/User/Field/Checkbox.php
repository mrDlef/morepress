<?php

namespace Morepress\User\Field;

class Checkbox extends \Morepress\User\Field
{

	/**
	 * @param $user
	 */
	public function render($user) {
		echo '<tr>';
		echo '<th>' . $this->_params['label'] . '</th>';
		echo '
			<td>
				<label for="' . $this->_id . '">
					<input type="hidden" name="' . $this->_name . '" value="0">
					<input type="checkbox" name="' . $this->_name . '" id="' . $this->_id . '"' . ($this->_value($user) ? ' checked' : '') . ' value="1" '.$this->_inputAttr().'>
					' . $this->_params['description'] . '
				</label>
			</td>';
		echo '</tr>';
	}

	/**
	 * @param $user_id
	 */
	public function save($user_id) {
		update_user_meta($user_id, $this->_name, (bool) $_POST[$this->_name]);
	}

}
