<?php

namespace Morepress\Taxonomy\Field;

class Image extends \Morepress\Taxonomy\Field
{
	protected $_taxonomy;
	protected $_type = 'image';
	protected $_slug;
	protected $_params = array();

	public function callback($term = null)
	{
		if (is_object($term)) {
			$term_id = $term->term_id;
			$term_meta = get_option('taxonomy_term_' . $term_id);
			$image = null;
			if ($term_meta) {
				$image = wp_get_attachment_image_src($term_meta[$this->_slug], 'medium');
				$image = $image[0];
			}
			?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="term_meta[<?php echo $this->_slug; ?>]"><?php echo $this->_params['label']; ?></label>
				</th>
				<td>
					<input name="term_meta[<?php echo $this->_slug; ?>]" type="hidden" class="custom_upload_image" value="<?php echo esc_attr($term_meta[$this->_slug]) ? esc_attr($term_meta[$this->_slug]) : ''; ?>">
					<img src="<?php echo $image; ?>" class="custom_preview_image" alt="">
					<p>
						<input class="custom_upload_image_button button" type="button" value="Choisir une image">
						<a href="#" class="custom_clear_image_button button">Supprimer l'image</a>
					</p>
					<?php if(! empty($this->_params['description'])) : ?>
						<p class="description"><?php echo $this->_params['description']; ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<?php
		}
	}
}
