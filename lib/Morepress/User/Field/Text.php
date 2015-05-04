<?php

namespace Morepress\User\Field;

class Text extends \Morepress\User\Field
{

	public function render($user) {
		echo '<tr>';
		echo '<th><label for="' . $this->_id . '">' . $this->_params['label'] . '</label></th>';
		echo '
			<td>
				<input type="text" name="' . $this->_name . '" id="' . $this->_id . '" value="'.get_the_author_meta($this->_name, $user->ID).'" class="regular-text">
				' . (empty($this->_params['description']) ? '<p class="description">' . $this->_params['description'] . '</p>' : '') . '
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
