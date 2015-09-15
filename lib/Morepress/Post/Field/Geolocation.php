<?php

namespace Morepress\Post\Field;

class Geolocation extends \Morepress\Post\Field {

    protected $_prefix_id = '';

    public function html($meta, $repeatable = null) {
        is_array($meta);
        $name = is_null($repeatable) ? $this->_name : $this->_name . '[' . $repeatable . ']';
        $id = is_null($repeatable) ? $this->_id : $this->_id . '_' . $repeatable;
        $classes = array();
        if (!empty($params['context']) and $params['context'] != 'side') {
            $classes[] = 'large-text';
        }
        $classes = implode(' ', $classes);
        empty($classes) or $classes = ' class="' . $classes . '"';
        echo '<tr class="form-field">';
        echo '
			<th>
				<label for="' . $id . '_lat">' . $this->_label . '</label>
			</th>
			<td>
        ';
        is_array($meta['lat']) and $meta['lat'] = null;
        is_array($meta['lng']) and $meta['lng'] = null;
		echo '<label for="' . $id . '_lat">Latitude</label><input type="text" value="' . $meta['lat'] . '" name="' . $name . '[lat]" id="' . $id . '_lat" '.$this->_inputAttr().'>';
        echo '<label for="' . $id . '_lng">Longitude</label><input type="text" value="' . $meta['lng'] . '" name="' . $name . '[lng]" id="' . $id . '_lng" '.$this->_inputAttr().'>';
        if (!empty($this->_description)) {
            echo '<p class="description">' . $this->_description . '</p>';
        }
        echo '</td>';
        echo '</tr>';
    }

	public function pre_save($post_id, $new, $old) {
        if(empty($new['lat']) or empty($new['lng']) and ! empty($this->_params['geocode'])) {
            $address = array();
            foreach($this->_params['geocode'] as $field) {
                empty($_POST[$field]) or $address[] = $_POST[$field];
            }
            if(! empty($address)) {
                try {
                    $curl     = new \Ivory\HttpAdapter\CurlHttpAdapter();
                    $geocoder = new \Geocoder\Provider\GoogleMaps($curl);

                    $address_collection = $geocoder->geocode(implode(' ', $address));

                    if(! empty($address_collection)) {
                        $new['lat'] = $address_collection->first()->getLatitude();
                        $new['lng'] = $address_collection->first()->getLongitude();
                    }
                } catch (\Exception $ex) {}

            }
        }
		return parent::pre_save($post_id, $new, $old);
	}

}
