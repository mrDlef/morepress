<?php 

namespace Morepress\Field;

class Checkbox extends \Morepress\Field
{
	
	protected $_prefix_id = '';
	public function html($meta, $repeatable = null){
		$name = is_null($repeatable) ? $this->_name : $this->_name.'['.$repeatable.']';
		$id = is_null($repeatable) ? $this->_id : $this->_id.'_'.$repeatable;
		echo '<tr class="form-field">';
		echo '
			<th></th>
			<td>
				<label for="'.$id . '">
					<input type="hidden" value="0" name="'.$name.'">
					<input type="checkbox" value="1" name="'.$name.'" id="'.$id . '" '. ($meta ? ' checked="checked"' : '') .'>
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