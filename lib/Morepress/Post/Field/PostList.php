<?php

namespace Morepress\Post\Field;

class PostList extends \Morepress\Post\Field
{

	protected $_prefix_id = '';

	public function __construct($slug, $desc = null, $params = array()) {
		parent::__construct($slug, $desc, $params);
		add_action('wp_enqueue_scripts', function() {
			wp_enqueue_script('suggest');
		});
		add_action('wp_ajax_morepress_'.$this->_id.'_ajax', array($this, 'ajax'));
		add_action('wp_ajax_nopriv_morepress_'.$this->_id.'_ajax', array($this, 'ajax'));
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

	public function html($meta, $repeatable = null){
		is_array($meta) and $meta = null;
		$name = is_null($repeatable) ? $this->_name : $this->_name.'['.$repeatable.']';
		$id = is_null($repeatable) ? $this->_id : $this->_id.'_'.$repeatable;
		$items = $this->_get_posts();
		empty($meta) or $post = get_post($meta);
		echo '<tr class="form-field">';
		echo '
			<th>
				<label for="'.$id . '">'.$this->_label.'</label>
			</th>
			<td>
				<input data-callback="'.$this->_id.'" type="text" class="morepress_post_list" value="'.(isset($post->post_title) ? $post->post_title : '').'" id="'.$id . '" placeholder="Commencez à tapper...">
				<input type="hidden" value="'.(isset($post->ID) ? $post->ID : '').'" name="'.$name.'">
		';
		if(!empty($this->_description))
		{
			echo '<p class="description">' . $this->_description . '</p>';
		}
		echo '</td>';
		echo '</tr>';
	}

}