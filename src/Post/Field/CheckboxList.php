<?php

namespace Morepress\Post\Field;

class CheckboxList extends \Morepress\Post\Field
{

	protected $_prefix_id = '';

	public function html($meta, $repeatable = null){
        $meta = maybe_unserialize($meta);
		$name = is_null($repeatable) ? $this->_name : $this->_name.'['.$repeatable.']';
		$id = is_null($repeatable) ? $this->_id : $this->_id.'_'.$repeatable;
        $classes = array();
        if(! empty($this->_params['context']) and $this->_params['context'] != 'side')
        {
            $classes[] = 'form-field';
        }
        $classes = implode(' ', $classes);
        empty($classes) or $classes = ' class="'.$classes.'"';
		echo '<tr'.$classes.'>';
		echo '
			<th scope="row">
				<label for="'.$id . '">'.$this->_label.'</label>
			</th>
			<td>
        ';
		foreach($this->_params['options'] as $key=>$value)
		{
            echo '
                <label for="'.$id . '_'.$key.'">
                    <input type="hidden" value="0" name="'.$id . '['.$key.']">
                    <input type="checkbox" value="1" name="'.$id . '['.$key.']" id="'.$id . '_'.$key.'" '. (empty($meta[$key]) ? '' : ' checked') .' '.$this->_inputAttr().'>
                    '.$value.'
                </label>
            ';
		}
		if(!empty($this->_description))
		{
			echo '<p class="description">' . $this->_description . '</p>';
		}
		echo '</td>';
		echo '</tr>';
	}
}