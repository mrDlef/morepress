<?php

namespace Morepress\Field;

class Select extends \Morepress\Field
{

	protected $_prefix_id = '';

	public function html($meta, $repeatable = null){
		$name = is_null($repeatable) ? $this->_name : $this->_name.'['.$repeatable.']';
		$id = is_null($repeatable) ? $this->_id : $this->_id.'_'.$repeatable;
		echo '<tr class="form-field">';
		echo '
			<th>
				<label for="'.$id . '">'.$this->_label.'</label>
			</th>
			<td>
				<select name="'.$name.'" id="'.$id . '" '.$this->_inputAttr().'>
		';
		foreach($this->_params['options'] as $key=>$value)
		{
			echo '<option '.(($key == $meta) ? 'selected' : '').' value="'.$key.'">'.$value.'</option>';
		}
		echo '</select>';
		if(!empty($this->_description))
		{
			echo '<p class="description">' . $this->_description . '</p>';
		}
		echo '</td>';
		echo '</tr>';
	}
}