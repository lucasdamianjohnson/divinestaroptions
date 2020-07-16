<?php
/**
* Example Custom Option Type.
*
* This is an example of a custom option type.
* 
* @category   Options
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha .2
*/
class CustomOptionType extends Option 
{


	public function generate_save_data_structure($type,$save_data,$mode=null) : array
	{

		return array($this->get_value_structure($type,$save_data,$mode));
	}

 	public function load_from_xml($option) : array
	{
		$type = (string) $option['type'];
		$mode = (string) $option['mode'];
		$value = (string) $option->value;
		return $this->get_data_strcutre($type,$value,$mode);
	}



 public function get_value_structure($type,$args,$mode = null) : array {
  return array();
}
 public function get_data_strcutre($type,$args,$mode = null) : array {
	return  array(
					'value' => $args,
					'type' => $type 
				); 
}
 public function get_html($type,$option,$value,$args=null) : string {
		$name = $option->name;
		$label = $option->label;
		$description = $option->description;
		$id = $option->name . '-id';
		$did = $option->name . '-description';
		return <<<HTML
<tr>
<th scope="row">
<label for="{$id}">$label</label>
</th>
<td>
<input name="divinestaroptions[customtype][{$name}]" type="text" id="{$id}" aria-describedby="{$did}" value="{$value}" class="regular-text">
<p class="description" id="{$did}">$description</p>
</td>
</tr>
HTML;
}
 public function is_type($type) : bool {
	$types = array(
		'customtype1',
		'customtype2'
	);

	 return array_search($type, $types ) !== false;
}
}