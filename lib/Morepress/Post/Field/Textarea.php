<?php

namespace Morepress\Post\Field;

class Textarea extends \Morepress\Post\Field
{

	protected $_prefix_id = '';
	public function html($meta, $repeatable = null){
		is_array($meta) and $meta = null;
		$name = is_null($repeatable) ? $this->_name : $this->_name.'['.$repeatable.']';
		$id = is_null($repeatable) ? $this->_id : $this->_id.'_'.$repeatable;
        $classes = array();
        if(! empty($params['context']) and $params['context'] != 'side')
        {
            $classes[] = 'large-text';
        }
        $classes = implode(' ', $classes);
        empty($classes) or $classes = ' class="'.$classes.'"';
		echo '<tr class="form-field">';
		echo '
			<th>
				<label for="'.$id . '">'.$this->_label.'</label>
			</th>
			<td>
				<textarea value="" name="'.$name.'" id="'.$id . '" rows="5"'.$classes.'>'.$meta.'</textarea>';
		if(!empty($this->_description))
		{
			echo '<p class="description">' . $this->_description . '</p>';
		}
		echo '</td>';
		echo '</tr>';
	}
}