<?php 

namespace Morepress\Field;

class Text extends \Morepress\Field
{
	
	protected $_prefix_id = 'morepress_text_';
	public function html($meta){
		echo '<tr class=form-field">';
		echo '
			<th>
				<label for="'.$this->_id.'">'.$this->_label.'</label>
			</th>
			<td>
				<input type="text" value="'.$meta.'" name="'.$this->_name.'" id="'.$this->_id.'">';
		if(!empty($this->_description))
		{
			echo '<p class="description">' . $this->_description . '</p>';
		}
		echo '</td>';
		echo '</tr>';
	}
}