<?php

namespace Morepress\Taxonomy\Field;

class PostList extends \Morepress\Taxonomy\Field
{
	protected $_type = 'postList';

	public function __construct($taxonomy, $slug, $params = array())
    {
		parent::__construct($taxonomy, $slug, $params);
		add_action('wp_enqueue_scripts', function() {
			wp_enqueue_script('suggest');
		});
		add_action('wp_ajax_morepress_'.$this->_slug.'_ajax', array($this, 'ajax'));
		add_action('wp_ajax_nopriv_morepress_'.$this->_slug.'_ajax', array($this, 'ajax'));
	}

	public function callback($term = null)
	{
        if (! empty($term)) {
            $mp_term = \Morepress\Term::forge($term);
            $meta = $mp_term->getMeta('page');
            empty($meta) or $post = get_post($meta);
        ?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="term_meta_<?php echo $this->_slug; ?>"><?php echo $this->_params['label']; ?></label>
				</th>
				<td>
                    <input data-callback="<?php echo $this->_slug; ?>" type="text" class="morepress_post_list" id="term_meta_<?php echo $this->_slug; ?>" value="<?php echo isset($post->post_title) ? esc_attr($post->post_title) : ''; ?>" placeholder="Commencez à tapper...">
                    <input type="hidden" value="<?php echo isset($post->ID) ? $post->ID : ''; ?>" name="term_meta[<?php echo $this->_slug; ?>]">
					<?php if(! empty($this->_params['description'])) : ?>
						<p class="description"><?php echo $this->_params['description']; ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<?php
		}
	}

	public function ajax()
	{
		$items = $this->_get_posts();
		die(json_encode($items));
	}

	protected function _get_posts()
	{
		$params = array(
			'posts_per_page' => -1,
		);
		empty($_GET['term']) or $params['s'] = stripslashes($_GET['term']);
		$params = array_merge($params, $this->_params['query']);
		return get_posts($params);
	}

}