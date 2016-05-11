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
        if (! empty($term)) {
            $mp_term = \Morepress\Term::forge($term);
			$value = $mp_term->getMeta($this->_slug, true);
			$image = null;
			if ($value) {
				$image = wp_get_attachment_image_src($value, 'original');
				$image = $image[0];
			}
        ?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="term_meta_<?php echo $this->_slug; ?>"><?php echo $this->_params['label']; ?></label>
				</th>
				<td>
					<input id="term_meta_<?php echo $this->_slug; ?>" name="term_meta[<?php echo $this->_slug; ?>]" type="hidden" class="upload_image" value="<?php echo $value ? esc_attr($value) : ''; ?>">
                    <div class="upload_preview">
                        <img src="<?php echo $image; ?>" class="preview_image" height="150" alt="">
                    </div>
					<p>
						<input class="upload_image_button button" type="button" value="Choisir une image">
						<a href="#" class="clear_image_button button">Supprimer l'image</a>
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
