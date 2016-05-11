<?php

namespace Morepress\Post\Field;

class File extends \Morepress\Post\Field
{

	protected $_prefix_id = '';
	public function html($meta, $repeatable = null){
		is_array($meta) and $meta = null;
		$name = is_null($repeatable) ? $this->_name : $this->_name.'['.$repeatable.']';
		$id = is_null($repeatable) ? $this->_id : $this->_id.'_'.$repeatable;
		$file = null;
		if ($meta) {
			$file = get_post($meta);
		}
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
				<input name="'.$name.'" type="hidden" class="upload" value="'.$meta.'">
                <div class="upload_preview"><a href="'.($file ? $file->guid : '').'" target="_blank">'.($file ? $file->post_title : '').'</a></div>
				<p>
					<input class="upload_button button" type="button" value="Choisir un fichier">
					<a href="#" class="clear_button button">Supprimer le fichier</a>
				</p>
			';
		if(!empty($this->_description))
		{
			echo '<p class="description">' . $this->_description . '</p>';
		}
		echo '</td>';
		echo '</tr>';
	}
}