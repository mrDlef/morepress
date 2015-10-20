<?php 

namespace Morepress\User\Field;

class Image extends \Morepress\User\Field
{

	/**
	 * @param $user
	 */
	public function render($user)
	{
		
		$meta = $this->_value($user);
		$image = null;
		if ($meta) {
			$image = wp_get_attachment_image_src($meta, 'original');
			$image = $image[0];
		}
		echo '<tr>';
		echo '<th><label for="'.$this->_id.'">'.$this->_params['label'].'</label></th>';
		echo '<td>
			<input name="'.$this->_name.'" type="hidden" class="upload_image" value="'.$meta.'" '.$this->_inputAttr().'>
			<img src="'.$image.'" class="preview_image" height="150" alt="">
			<p>
				<input class="upload_image_button button" type="button" value="Choisir une image">
				<a href="#" class="clear_image_button button">Supprimer l\'image</a>
			</p>
			' . $this->_description() . '
		</td>';
		echo '</tr>';
	}

	/**
	 * @param $user_id
	 */
	public function save($user_id) {
		update_user_meta($user_id, $this->_name, (int) $_POST[$this->_name]);
	}
}