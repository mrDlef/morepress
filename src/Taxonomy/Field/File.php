<?php

namespace Morepress\Taxonomy\Field;

use \Morepress\Taxonomy\Field;

class File extends Field {

    protected $_taxonomy;
    protected $_type = 'file';
    protected $_slug;
    protected $_params = array();

    public function callback($term = null) {
        if (! empty($term)) {
            $mp_term = \Morepress\Term::forge($term);
			$value = $mp_term->getMeta($this->_slug, true);
			?>
			<tr class="form-field">
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
					<p>
						<input class="upload_button button" type="button" value="Choisir un fichier">
						<a href="#" class="clear_button button">Supprimer le fichier</a>
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
