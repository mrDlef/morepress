<?php

namespace Morepress\Taxonomy\Field;

class Checkbox extends \Morepress\Taxonomy\Field
{
	protected $_taxonomy;
	protected $_type = 'checkbox';
	protected $_slug;
	protected $_params = array();

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
                    <label for="term_meta_<?php echo $this->_slug; ?>">
                        <input name="term_meta[<?php echo $this->_slug; ?>]" type="hidden" value="0">
                        <input <?php echo $mp_term->getMeta($this->_slug, true) ? ' checked' : ''; ?> id="term_meta_<?php echo $this->_slug; ?>" name="term_meta[<?php echo $this->_slug; ?>]" type="checkbox" value="1">
                        <?php if(! empty($this->_params['description'])) : ?>
                            <?php echo $this->_params['description']; ?>
                        <?php endif; ?>
                    </label>
				</td>
			</tr>
			<?php
		}
	}
}
