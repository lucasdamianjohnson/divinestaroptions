<?php
/**
* Set class structure for option types.
*
* This is a temple class for the option types. 
* 
* @category   Options
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha .2
*/
class OptionGroups extends Option
{

	private $options;

	public function __construct() {

		$this->options = new Options(false);
	}

	public function generate_save_data_structure($type,$save_data,$mode=null) : array
	{
	
	
  	  $values = array();
  	  foreach($save_data as $type => $data) {
 		   foreach ($data as $key => $value) {

 		   	$saved_data = $this->options->generate_save_data_structure($type,$value)[0];

 		   	 $values[$key]  = $this->options->get_data_strcutre($type,$saved_data,$mode);
 		   }

	
	} 
		$return = array($this->get_value_structure($type,$values,$mode))[0];

		return $return;
	}

 	public function load_from_xml($option) : array
	{
	  $type = (string) $option['type'];
 	  $name = (string) $option['name'];
 	  $mode = (string) $option['mode'];


  	  $values = array();
  	  foreach($option as $op) {

  	  	  if(isset($op['name'])){
  	  	  $oname = (string) $op['name'];
  	  	  } else {
  	  	  $oname = (string) $op->name;
  	  	  }
    	  $values[$oname]  = $this->options->load_from_xml($op);
	
	}  



		return $this->get_data_strcutre($type,$values,$mode);
	}



 public function get_value_structure($type,$args,$mode = null) : array {
  	return array($args);
}
 public function get_data_strcutre($type,$args,$mode = null) : array {
	return  array(
					'value' => $args,
					'type' => $type 
				); 
}




 public function get_html($type,$option,$value,$args=null) : string {

 	  $groupname = (string) $option['name'];
 	  $groupmode = (string) $option['mode'];
 	  $args = [
 	  	'optiongroup' => true,
 	  	'groupname' => $groupname
 	  ];


  	  $return_html  = '';
  	  foreach($option as $op) {
  	  	  $otype = (string)$op['type'];

  	  	  if(isset($op['name'])){
  	  	  $oname = (string) $op['name'];
  	  	  } else {
  	  	  $oname = (string) $op->name;
  	  	  }
  	  	  $ovalue = $value[$oname]['value'];

    	  $return_html  .= $this->options->get_html($otype,$op,$ovalue,$args);
	
	}  


	if($return_html != ''){
	return $return_html;
	}


 	$return = $this->return_form_error('This is an option group error.');
 	return $return;
}







 public function is_type($type) : bool {
	$types = array(
		'optiongroup',
	);

	 return array_search($type, $types ) !== false;
}


}