<?php

namespace Morepress\Field;

class Url extends \Morepress\Field
{

	protected $_prefix_id = '';

	public function html($meta){
		echo '<tr class=form-field">';
		echo '
			<th>
				<label for="'.$this->_id.'">'.$this->_label.'</label>
			</th>
			<td>
				<input type="url" value="'.$meta.'" name="'.$this->_name.'" id="'.$this->_id.'">';
		if(!empty($this->_description))
		{
			echo '<p class="description">' . $this->_description . '</p>';
		}
		echo '</td>';
		echo '</tr>';
	}
}