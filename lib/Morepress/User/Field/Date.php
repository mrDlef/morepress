<?php

namespace Morepress\User\Field;

class Date extends \Morepress\User\Field
{

	public function render($user) {
        global $wp_locale;
		echo '<tr>';
		echo '<th><label for="' . $this->_id . '">' . $this->_params['label'] . '</label></th>';
        echo '<td>';

        $time_adj = current_time('timestamp');
        $date = $this->_value($user) /*? /*$user*/;
        $jj = ($date) ? mysql2date( 'd', $date, false ) : gmdate( 'd', $time_adj );
        $mm = ($date) ? mysql2date( 'm', $date, false ) : gmdate( 'm', $time_adj );
        $aa = ($date) ? mysql2date( 'Y', $date, false ) : gmdate( 'Y', $time_adj );

        $month = '<label><span class="screen-reader-text">' . __( 'Month' ) . '</span><select id="'.$this->_id.'_mm" name="'.$this->_name.'[mm]"' . ">\n";
        for ( $i = 1; $i < 13; $i = $i +1 ) {
            $monthnum = zeroise($i, 2);
            $monthtext = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
            $month .= "\t\t\t" . '<option value="' . $monthnum . '" data-text="' . $monthtext . '" ' . selected( $monthnum, $mm, false ) . '>';
            $month .= sprintf( __( '%1$s-%2$s' ), $monthnum, $monthtext ) . "</option>\n";
        }
        $month .= '</select></label>';

        $day = '<label><span class="screen-reader-text">' . __( 'Day' ) . '</span><input type="text" id="'.$this->_id.'_jj" name="'.$this->_name.'[jj]" value="' . $jj . '" size="2" maxlength="2"' . ' autocomplete="off" /></label>';
        $year = '<label><span class="screen-reader-text">' . __( 'Year' ) . '</span><input type="text" id="'.$this->_id.'_aa" name="'.$this->_name.'[aa]" value="' . $aa . '" size="4" maxlength="4"' . ' autocomplete="off" /></label>';

        echo $day.$month.$year;

        echo $this->_description();
        echo '</td>';
		echo '</tr>';
	}

	/**
	 * @param $user_id
	 */
	public function save($user_id) {
        $date = array(
            sanitize_text_field($_POST[$this->_name]['aa']),
            sanitize_text_field($_POST[$this->_name]['mm']),
            sanitize_text_field($_POST[$this->_name]['jj']),
        );
		update_user_meta($user_id, $this->_name, implode('-', $date));
	}
}