<?php 

namespace Morepress\Field;

class PostList extends \Morepress\Field
{
	
	protected $_prefix_id = 'morepress_post_list_';

	public function __construct($slug, $desc = null, $params = array()) {
		parent::__construct($slug, $desc, $params);
		add_action('wp_enqueue_scripts', function() {
			wp_enqueue_script('suggest');
		});
		add_action('wp_ajax_morepress_postlist_ajax', array($this, 'ajax'));
		add_action('wp_ajax_nopriv_morepress_postlist_ajax', array($this, 'ajax'));
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

	public function html($meta){
		$items = $this->_get_posts();
		empty($meta) or $post = get_post($meta);
		echo '<tr class="form-field">';
		echo '
			<th>
				<label for="'.$this->_id.'">'.$this->_label.'</label>
			</th>
			<td>
				<input type="text" class="morepress_post_list" value="'.(isset($post->post_title) ? $post->post_title : '').'" id="'.$this->_id.'" placeholder="Commencez à tapper...">
				<input type="hidden" value="'.(isset($post->ID) ? $post->ID : '').'" name="'.$this->_name.'">
		';
		if(!empty($this->_description))
		{
			echo '<p class="description">' . $this->_description . '</p>';
		}
		echo '</td>'; 
		echo '</tr>';
	}

}