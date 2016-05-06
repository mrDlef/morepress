<?php

namespace Morepress\Taxonomy\Field;

class Editor extends \Morepress\Taxonomy\Field
{
	protected $_taxonomy;
	protected $_type = 'editor';
	protected $_slug;
	protected $_params = array();

	public function callback($term = null)
	{
        if (! empty($term)) {
            $mp_term = \Morepress\Term::forge($term);
        ?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="term_meta[<?php echo $this->_slug; ?>]"><?php echo $this->_params['label']; ?></label>
				</th>
				<td>
                    <input type="hidden" name="term_meta_editor[]" value="<?php echo $this->_slug; ?>">
					<?php wp_editor($mp_term->getMeta($this->_slug, true), $this->_slug, $this->_params['settings']); ?>
					<?php if(! empty($this->_params['description'])) : ?>
						<p class="description"><?php echo $this->_params['description']; ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<?php
		}
	}
}
