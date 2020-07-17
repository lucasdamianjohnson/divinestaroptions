<?php 
/**
* Handle generic option types.
*
* This handles the generic option types. 
* 
* @category   Options
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha .2
*/
class Generic extends Option 
{
		private $helper;
	public function set_helper($helper) {
		$this->helper = $helper;
	}


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
		return '';
	}

	public function is_type($type) : bool{
		$types = array(
			'generic'
		);
		return (string)$type === 'generic';
		//return array_search($type, $types ) !== false;
	}

}