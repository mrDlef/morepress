<?php

namespace Morepress\Post\Field;

class Date extends \Morepress\Post\Field {

    protected $_prefix_id = '';

    public function html($meta, $repeatable = null) {
        global $wp_locale;
        is_array($meta);
        $name = is_null($repeatable) ? $this->_name : $this->_name . '[' . $repeatable . ']';
        $id = is_null($repeatable) ? $this->_id : $this->_id . '_' . $repeatable;
        echo '<tr>';
        echo '
			<th scope="row">
				<label for="' . $id . '_jj">' . $this->_label . '</label>
			</th>
			<td>
        ';
        $jj = ($meta) ? mysql2date('d', $meta, false) : '';
        $mm = ($meta) ? mysql2date('m', $meta, false) : '';
        $aa = ($meta) ? mysql2date('Y', $meta, false) : '';

        $month = '<label><span class="screen-reader-text">' . __('Month') . '</span><select id="' . $id . '_mm" name="' . $name . '[mm]"' . ' '.$this->_inputAttr().'>';
        $month .= "\t\t\t" . '<option value="" data-text="" ' . selected('', $mm, false) . '>';
        $month .= "--</option>\n";
        for ($i = 1; $i < 13; $i = $i + 1) {
            $monthnum = zeroise($i, 2);
            $monthtext = $wp_locale->get_month_abbrev($wp_locale->get_month($i));
            $month .= "\t\t\t" . '<option value="' . $monthnum . '" data-text="' . $monthtext . '" ' . selected($monthnum, $mm, false) . '>';
            $month .= sprintf(__('%1$s-%2$s'), $monthnum, $monthtext) . "</option>\n";
        }
        $month .= '</select></label>';

        $day = '<label><span class="screen-reader-text">' . __('Day') . '</span><input type="number" min="1" max="31" id="' . $id . '_jj" name="' . $name . '[jj]" value="' . $jj . '" size="2" maxlength="2"' . ' autocomplete="off" ' . $this->_inputAttr() . '></label>';
        $year = '<label><span class="screen-reader-text">' . __('Year') . '</span><input type="number" id="' . $id . '_aa" name="' . $name . '[aa]" value="' . $aa . '" size="4" maxlength="4"' . ' autocomplete="off" ' . $this->_inputAttr() . '></label>';

        echo $day . $month . $year;

        if (!empty($this->_description)) {
            echo '<p class="description">' . $this->_description . '</p>';
        }
        echo '</td>';
        echo '</tr>';
    }

	public function pre_save($post_id, $new, $old) {
        empty($new['aa']) and $new['aa'] = date('Y');
        empty($new['mm']) and $new['mm'] = date('m');
        empty($new['jj']) and $new['jj'] = date('d');
        $new['aa'] = sprintf('%04d', $new['aa']);
        $new['mm'] = sprintf('%02d', $new['mm']);
        $new['jj'] = sprintf('%02d', $new['jj']);
        $new = implode('-', array($new['aa'], $new['mm'], $new['jj']));
		return parent::pre_save($post_id, $new, $old);
	}

}
