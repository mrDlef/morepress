<?php 

namespace Morepress\Field;

class Textarea extends \Morepress\Field
{
	
	protected $_prefix_id = 'morepress_textarea_';
	public function html($meta){
		echo '<tr class=form-field">';
		echo '
			<th>
				<label for="'.$this->_id.'">'.$this->_label.'</label>
			</th>
			<td>
				<textarea value="" name="'.$this->_name.'" id="'.$this->_id.'">'.$meta.'</textarea>';
		if(!empty($this->_description))
		{
			echo '<p class="description">' . $this->_description . '</p>';
		}
		echo '</td>';
		echo '</tr>';
	}
}