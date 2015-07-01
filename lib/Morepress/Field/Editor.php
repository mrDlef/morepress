<?php

namespace Morepress\Field;

class Editor extends \Morepress\Field {

	protected $_prefix_id = '';

	public function html($meta, $repeatable = null) {
		$name = is_null($repeatable) ? $this->_name : $this->_name.'['.$repeatable.']';
		$id = is_null($repeatable) ? $this->_id : $this->_id.'_'.$repeatable;
		echo '<tr class="form-field">';
		echo '
			<th>
				<label for="' . $id . '">' . $this->_label . '</label>
			</th>
			<td>
        ';
		if (!empty($this->_params['disabled']))
		{
			echo apply_filters('the_content', $meta);
		} else
		{
			wp_editor($meta, $this->_id);
		}

		if (!empty($this->_description)) {
			echo '<p class="description">' . $this->_description . '</p>';
		}
		echo '</td>';
		echo '</tr>';
	}

}
