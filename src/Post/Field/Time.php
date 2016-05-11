<?php

namespace Morepress\Post\Field;

class Time extends \Morepress\Post\Field {

    protected $_prefix_id = '';

    public function html($meta, $repeatable = null) {
        is_array($meta);
        $name = is_null($repeatable) ? $this->_name : $this->_name . '[' . $repeatable . ']';
        $id = is_null($repeatable) ? $this->_id : $this->_id . '_' . $repeatable;
        echo '<tr>';
        echo '
			<th scope="row">
				<label for="' . $id . '_hh">' . $this->_label . '</label>
			</th>
			<td>
        ';

        $hh = ($meta) ? mysql2date( 'H', $meta, false ) : '';
        $mn = ($meta) ? mysql2date( 'i', $meta, false ) : '';

        $hour = '<label><span class="screen-reader-text">' . __( 'Hour' ) . '</span><input type="number" min="0" max="23" id="' . $id . '_hh" name="' . $name . '[hh]" value="' . $hh . '" size="2" maxlength="2" autocomplete="off" ' . $this->_inputAttr() . '></label>';
        $minute = '<label><span class="screen-reader-text">' . __( 'Minute' ) . '</span><input type="number" min="0" max="59" id="' . $id . '_mn" name="' . $name . '[mn]" value="' . $mn . '" size="2" maxlength="2" autocomplete="off" ' . $this->_inputAttr() . '></label>';

        echo $hour.$minute;

        if (!empty($this->_description)) {
            echo '<p class="description">' . $this->_description . '</p>';
        }
        echo '</td>';
        echo '</tr>';
    }

	public function pre_save($post_id, $new, $old) {
        empty($new['hh']) and $new['hh'] = date('H');
        empty($new['mn']) and $new['mn'] = date('i');
        $new['hh'] = sprintf('%02d', $new['hh']);
        $new['mn'] = sprintf('%02d', $new['mn']);
        $new = implode('-', array($new['hh'], $new['mn']));
		return parent::pre_save($post_id, $new, $old);
	}

}
