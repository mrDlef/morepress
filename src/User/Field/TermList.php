<?php

namespace Morepress\User\Field;

class TermList extends \Morepress\User\Field {

    public function render($user) {

        $args = array(
            'selected_cats' => $this->_values($user),
            'echo' => false,
        );
        empty($this->_params['args']) or $args = array_merge($args, $this->_params['args']);
        echo '<tr>';
        echo '<th><label for="' . $this->_id . '">' . $this->_params['label'] . '</label></th>';
        echo '
			<td>
                ' .  wp_terms_checklist(0, $args) . '
				' . $this->_description() . '
			</td>
		';
        echo '</tr>';
    }

    /**
     * @param $user_id
     */
    public function save($user_id) {
        delete_user_meta($user_id, $this->_name);
        foreach($_POST['tax_input'][$this->_params['args']['taxonomy']] as $term_id) {
            add_user_meta($user_id, $this->_name, $term_id, false);
        }

    }

}
