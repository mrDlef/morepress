<?php

namespace Morepress\Taxonomy\Field;

use \Morepress\Taxonomy\Field;

class File extends Field {

    protected $_taxonomy;
    protected $_type = 'file';
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

    public function callback($term = null) {
        if (! empty($term)) {
            $mp_term = \Morepress\Term::forge($term);
			$value = $mp_term->getMeta($this->_slug, true);
			?>
			<tr class="form-field form-field-file">
				<th scope="row" valign="top">
					<label for="term_meta_<?php echo $this->_slug; ?>"><?php echo $this->_params['label']; ?></label>
				</th>
				<td>
					<input id="term_meta_<?php echo $this->_slug; ?>" name="term_meta[<?php echo $this->_slug; ?>]" type="hidden" class="upload" value="<?php echo $value ? esc_attr($value) : ''; ?>">
                    <div class="upload_preview">
                        <a href="<?php echo esc_attr($value); ?>" target="_blank">
                            <?php echo $value; ?>
                        </a>
                    </div>
					<p class="hide-if-no-js">
						<button class="upload_button button<?php echo $value ? ' hidden' : ''; ?>" type="button">Choisir une fichier</button>
						<button class="clear_button button<?php echo $value ? '' : ' hidden'; ?>" type="button">Supprimer le fichier</button>
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
