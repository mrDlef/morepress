<?php

namespace Morepress\Post\Field;

class ColorPicker extends \Morepress\Post\Field {

    protected $_prefix_id = '';

    public function __construct($slug, $desc = null, $params = array()) {
        parent::__construct($slug, $desc, $params);
        add_action('admin_enqueue_scripts', array($this, 'action_admin_enqueue_scripts'));
    }

    public function action_admin_enqueue_scripts() {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('color-picker', plugins_url('js/color-picker.js', MOREPRESS_PLUGIN_FILE), array('wp-color-picker'), false, true);
    }

    public function html($meta, $repeatable = null) {
        is_array($meta) and $meta = null;
        $name = is_null($repeatable) ? $this->_name : $this->_name . '[' . $repeatable . ']';
        $id = is_null($repeatable) ? $this->_id : $this->_id . '_' . $repeatable;
        $classes = array();
        if (!empty($this->_params['context']) and $this->_params['context'] != 'side') {
            $classes[] = 'form-field';
        }
        $classes = implode(' ', $classes);
        empty($classes) or $classes = ' class="' . $classes . '"';
        echo '<tr' . $classes . '>';
        echo '
			<th scope="row">
				<label for="' . $id . '">' . $this->_label . '</label>
			</th>
			<td>
				<input data-toggle="color-picker" type="text" value="' . $meta . '" name="' . $name . '" id="' . $id . '" ' . $this->_inputAttr() . '>';
        if (!empty($this->_description)) {
            echo '<p class="description">' . $this->_description . '</p>';
        }
        echo '</td>';
        echo '</tr>';
    }
}
