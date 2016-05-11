<?php

namespace Morepress\Taxonomy\Field;

class ColorPicker extends \Morepress\Taxonomy\Field {

    protected $_prefix_id = '';

	public function __construct($taxonomy, $slug, $params = array())
	{
        parent::__construct($taxonomy, $slug, $params);
		add_action('admin_enqueue_scripts', array($this, 'action_admin_enqueue_scripts'));
	}

    public function action_admin_enqueue_scripts() {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('color-picker', plugins_url('js/color-picker.js', MOREPRESS_PLUGIN_FILE), array('wp-color-picker'), false, true);
    }


	public function callback($term = null)
	{
        if (! empty($term)) {
            $mp_term = \Morepress\Term::forge($term);
        ?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="term_meta_<?php echo $this->_slug; ?>"><?php echo $this->_params['label']; ?></label>
				</th>
				<td>
                    <input data-toggle="color-picker" id="term_meta_<?php echo $this->_slug; ?>" name="term_meta[<?php echo $this->_slug; ?>]" type="text" value="<?php echo esc_attr($mp_term->getMeta($this->_slug, true)); ?>">
					<?php if(! empty($this->_params['description'])) : ?>
						<p class="description"><?php echo $this->_params['description']; ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<?php
		}
	}
}
