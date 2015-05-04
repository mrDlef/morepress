<?php 

namespace Morepress\User\Field;

class Image extends \Morepress\User\Field
{

	/**
	 * @param $user
	 */
	public function render($user)
	{
		
		$meta = get_the_author_meta($this->_name, $user->ID);
		$image = null;
		if ($meta) {
			$image = wp_get_attachment_image_src($meta, 'medium');
			$image = $image[0];
		}
		echo '<tr>';
		echo '<th><label for="'.$this->_id.'">'.$this->_params['label'].'</label></th>';
		echo '<td>
			<input name="'.$this->_name.'" type="hidden" class="custom_upload_image" value="'.$meta.'">
			<img src="'.$image.'" class="custom_preview_image" alt="">
			<p>
				<input class="custom_upload_image_button button" type="button" value="Choisir une image">
				<a href="#" class="custom_clear_image_button button">Supprimer l\'image</a>
			</p>
			'.(empty($this->_params['description']) ? '<p class="description">'.$this->_params['description'].'</p>' : '').'
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