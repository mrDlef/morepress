<?php

namespace Morepress\Post\Field;

class PostTypeList extends \Morepress\Post\Field
{

	protected $_prefix_id = '';

	protected function _get_post_types()
	{
            $return = array();
            $post_types = \Morepress\Post_Type::find($this->_params['args'], 'object');
            foreach($post_types as $name=>$post_type)
            {
                $return[$name] = $post_type->labels->name;
            }
            return $return;
	}

	public function html($meta, $repeatable = null){
		is_array($meta) and $meta = null;
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
			<th>
				<label for="'.$id . '">'.$this->_label.'</label>
			</th>
			<td>
				<select name="'.$name.'" id="'.$id . '" '.$this->_inputAttr().'" '.$this->_inputAttr().'>
		';
		foreach($this->_get_post_types() as $key=>$value)
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
