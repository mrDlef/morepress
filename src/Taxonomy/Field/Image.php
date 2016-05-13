<?php

namespace Morepress\Taxonomy\Field;

class Image extends \Morepress\Taxonomy\Field
{
	protected $_taxonomy;
	protected $_type = 'image';
	protected $_slug;
	protected $_params = array();

	public function __construct($taxonomy, $slug, $params = array())
	{
        parent::__construct($taxonomy, $slug, $params);
		add_action('admin_enqueue_scripts', array($this, 'action_admin_enqueue_scripts'));
	}

    public function action_admin_enqueue_scripts() {
        wp_enqueue_media();
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('upload', plugins_url('js/upload.js', MOREPRESS_PLUGIN_FILE), array('media-upload'));
    }

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
			<tr class="form-field form-field-image">
				<th scope="row" valign="top">
					<label for="term_meta_<?php echo $this->_slug; ?>"><?php echo $this->_params['label']; ?></label>
				</th>
				<td>
					<input id="term_meta_<?php echo $this->_slug; ?>" name="term_meta[<?php echo $this->_slug; ?>]" type="hidden" class="upload_image" value="<?php echo $value ? esc_attr($value) : ''; ?>">
                    <div class="upload_preview">
                        <img src="<?php echo $image; ?>" class="preview_image" height="150" alt="">
                    </div>
					<p class="hide-if-no-js">
						<button class="upload_image_button button<?php echo $value ? ' hidden' : ''; ?>" type="button">Choisir une image</button>
						<button class="clear_image_button button<?php echo $value ? '' : ' hidden'; ?>" type="button">Supprimer l'image</button>
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
