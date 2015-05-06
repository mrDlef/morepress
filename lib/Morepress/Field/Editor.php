<?php

namespace Morepress\Field;

class Editor extends \Morepress\Field {

	protected $_prefix_id = 'morepress_editor_';

	public function html($meta) {
		echo '<tr class=form-field">';
		echo '
			<th>
				<label for="' . $this->_id . '">' . $this->_label . '</label>
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
