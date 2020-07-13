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
 public function get_value_structure($type,$args,$mode = null) : array {
  return array();
}
 public function get_data_strcutre($type,$args,$mode = null) : array {
	return  array(
					'value' => $args,
					'type' => $type 
				); 
}
 public function get_html($type,$option,$value) : string {
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