<?php
/**
* Display form content. 
*
* This handles content option types such as custom HTML and headings. 
* 
* @category   Options
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha .2
*/
class Content extends Option 
{

	public function get_value_structure($type,$args,$mode = null) : array {

	return array();
}




    public function get_data_strcutre($type,$args,$mode = null) : array  {



    	return array();
    }


	public function get_html($type,$option,$value) : string {


		return $option->value;
	}


	public function is_type($type) : bool {
	$types = array(
		'formhtml'
	);

	 return array_search($type, $types ) !== false;
	}



}





?>