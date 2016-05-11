<?php

namespace Morepress\Post\Field;

class Term extends \Morepress\Post\Field
{

	protected $_prefix_id = '';

	public function render($user) {

        $args = array(
            'id' => $this->_name,
            'name' => $this->_name,
            'selected' => $this->_value($user),
            'echo' => 0,
        );
        empty($this->_params['args']) or $args = array_merge($args, $this->_params['args']);
		echo '<tr>';
		echo '<th scope="row"><label for="' . $this->_id . '">' . $this->_params['label'] . '</label></th>';
		echo '
			<td>
                '.  wp_dropdown_categories($args).'
				' . $this->_description() . '
			</td>
		';
		echo '</tr>';
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
        $args = array(
            'id' => $id,
            'name' => $name,
            'selected' => $meta,
            'echo' => 0,
            'class' => $classes,
        );
        empty($this->_params['args']) or $args = array_merge($args, $this->_params['args']);
		echo '<tr'.$classes.'>';
		echo '
			<th scope="row">
				<label for="'.$id . '">'.$this->_label.'</label>
			</th>

			<td>
                '.  wp_dropdown_categories($args).'
				<p class="description">' . $this->_description . '</p>
			</td>';
		echo '</tr>';
	}

}
