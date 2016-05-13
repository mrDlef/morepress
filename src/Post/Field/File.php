<?php

namespace Morepress\Post\Field;

class File extends \Morepress\Post\Field
{

	protected $_prefix_id = '';

	public function __construct($slug, $desc = null, $params = array()) {
        parent::__construct($slug, $desc, $params);
		add_action('admin_enqueue_scripts', array($this, 'action_admin_enqueue_scripts'));
	}

    public function action_admin_enqueue_scripts() {
        wp_enqueue_media();
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('upload', plugins_url('js/upload.js', MOREPRESS_PLUGIN_FILE), array('media-upload'));
    }

	public function html($meta, $repeatable = null){
		is_array($meta) and $meta = null;
		$name = is_null($repeatable) ? $this->_name : $this->_name.'['.$repeatable.']';
		$id = is_null($repeatable) ? $this->_id : $this->_id.'_'.$repeatable;
		$file = null;
		if ($meta) {
			$file = get_post($meta);
		}
        $classes = array();
        if(! empty($this->_params['context']) and $this->_params['context'] != 'side')
        {
            $classes[] = 'form-field';
            $classes[] = 'form-field-file';
        }
        $classes = implode(' ', $classes);
        empty($classes) or $classes = ' class="'.$classes.'"';
		echo '<tr'.$classes.'>';
		echo '
			<th scope="row">
				<label for="'.$id . '">'.$this->_label.'</label>
			</th>
			<td>
				<input name="'.$name.'" type="hidden" class="upload" value="'.$meta.'">
                <div class="upload_preview"><a href="'.($file ? $file->guid : '').'" target="_blank">'.($file ? $file->post_title : '').'</a></div>
                <p class="hide-if-no-js">
                    <button class="upload_button button'.($meta ? ' hidden' : '').'" type="button">Choisir un fichier</button>
                    <button class="clear_button button'.($meta ? '' : ' hidden').'" type="button">Supprimer le fichier</button>
                </p>
			';
		if(!empty($this->_description))
		{
			echo '<p class="description">' . $this->_description . '</p>';
		}
		echo '</td>';
		echo '</tr>';
	}
}