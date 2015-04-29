<?php 

namespace Morepress\Field;

class Checkbox extends \Morepress\Field
{
	
	protected $_prefix_id = 'morepress_checkbox_';
	public function html($meta){
		echo '<tr class="form-field">';
		echo '
			<th></th>
			<td>
				<label for="'.$this->_id.'">
					<input type="hidden" value="0" name="'.$this->_name.'">
					<input type="checkbox" value="1" name="'.$this->_name.'" id="'.$this->_id.'" '. ($meta ? ' checked="checked"' : '') .'>
					'.$this->_label.'
				</label>';
		if(!empty($this->_description))
		{
			echo '<p class="description">' . $this->_description . '</p>';
		}
		echo '</td>';
		echo '</tr>';
	}
}