<?php 

namespace Morepress\Field;

class Image extends \Morepress\Field
{
	
	protected $_prefix_id = 'morepress_image_';
	public function html($meta, $repeatable = null){
		$name = is_null($repeatable) ? $this->_name : $this->_name.'['.$repeatable.']';
		$id = is_null($repeatable) ? $this->_id : $this->_id.'_'.$repeatable;
		$image = null;
		if ($meta) {
			$image = wp_get_attachment_image_src($meta, 'original');
			$image = $image[0];
		}
		echo '<tr class="form-field">';
		echo '
			<th>
				<label for="'.$id . '">'.$this->_label.'</label>
			</th>
			<td>
				<input name="'.$name.'" type="hidden" class="custom_upload_image" value="'.$meta.'">
				<img src="'.$image.'" class="custom_preview_image" height="150" alt="">
				<p>
					<input class="custom_upload_image_button button" type="button" value="Choisir une image">
					<a href="#" class="custom_clear_image_button button">Supprimer l\'image</a>
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