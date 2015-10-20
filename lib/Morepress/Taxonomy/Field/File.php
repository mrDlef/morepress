<?php

namespace Morepress\Taxonomy\Field;

use \Morepress\Taxonomy\Field;

class File extends Field {

    protected $_taxonomy;
    protected $_type = 'file';
    protected $_slug;
    protected $_params = array();

    public function callback($term = null) {
		if (is_object($term)) {
			$term_id = $term->term_id;
			$term_meta = get_option('taxonomy_term_' . $term_id);
			?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="term_meta_<?php echo $this->_slug; ?>"><?php echo $this->_params['label']; ?></label>
				</th>
				<td>
					<input id="term_meta_<?php echo $this->_slug; ?>" name="term_meta[<?php echo $this->_slug; ?>]" type="hidden" class="upload" value="<?php echo esc_attr($term_meta[$this->_slug]) ? esc_attr($term_meta[$this->_slug]) : ''; ?>">
                    <div class="upload_preview">
                        <a href="<?php echo $term_meta[$this->_slug]; ?>" target="_blank">
                            <?php echo $term_meta[$this->_slug]; ?>
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
