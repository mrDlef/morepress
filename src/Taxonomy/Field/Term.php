<?php

namespace Morepress\Taxonomy\Field;

class Term extends \Morepress\Taxonomy\Field
{
	protected $_taxonomy;
	protected $_type = 'term';
	protected $_slug;
	protected $_params = array();

	public function callback($term = null)
	{
        if (! empty($term)) {
            $mp_term = \Morepress\Term::forge($term);
            $args = array(
                'id' => 'term_meta_'.$this->_slug,
                'name' => 'term_meta['.$this->_slug.']',
                'selected' => $mp_term->getMeta($this->_slug, true),
            );
            empty($this->_params['args']) or $args = array_merge($args, $this->_params['args']);
        ?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="term_meta_<?php echo $this->_slug; ?>"><?php echo $this->_params['label']; ?></label>
				</th>
				<td>
                    <?php wp_dropdown_categories($args); ?>
					<?php if(! empty($this->_params['description'])) : ?>
						<p class="description"><?php echo $this->_params['description']; ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<?php
		}
	}

}
