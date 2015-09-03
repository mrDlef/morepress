<?php

namespace Morepress\User\Field;

class Term extends \Morepress\User\Field
{

	public function render($user) {

        $args = array(
            'id' => $this->_name,
            'name' => $this->_name,
            'selected' => $this->_value($user),
            'echo' => 0,
        );
        empty($this->_params['args']) or $args = array_merge($args, $this->_params['args']);
		echo '<tr>';
		echo '<th><label for="' . $this->_id . '">' . $this->_params['label'] . '</label></th>';
		echo '
			<td>
                '.  wp_dropdown_categories($args).'
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
