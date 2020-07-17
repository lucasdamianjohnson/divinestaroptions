<?php
/**
* Set class structure for option groups.
*
* This handles option groups. 
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



	public function set_options($options) {
		//$this->options = $options;
	}
		
	private $helper;
	public function set_helper($helper) {
		$this->helper = $helper;
	}



	public function generate_save_data_structure($type,$save_data,$mode=null) : array
	{
		



  	  foreach($save_data as $ntype => $data) {
 		   foreach ($data as $key => $value) {

 	

 		   	$saved_data = $this->helper->get_options()
 		   	->generate_save_data_structure($ntype,$value)[0];

 		   	 $values[$key]  = $this->helper->get_options()->
 		   	 get_data_strcutre($ntype,$saved_data,$mode);
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
    	  $values[$oname]  = $this->helper->get_options()->load_from_xml($op);
	
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
 	  
 	  if($args == null) {
 	  	$args = array();
 	  }
 	  $args['optiongroup'] = true;
 	  $args['groupname'] = $groupname;

 	  


  	  $return_html  = '';
  	  foreach($option as $op) {
  	  	  $otype = (string)$op['type'];

  	  	  if(isset($op['name'])){
  	  	  $oname = (string) $op['name'];
  	  	  } else {
  	  	  $oname = (string) $op->name;
  	  	  }
  	  	  if(isset($value[$oname]['value'])){
  	  	  $ovalue = $value[$oname]['value'];
  	  	  }else{$ovalue = '';}

    	  $return_html  .=  $this->helper->get_options()
 		   	->get_html($otype,$op,$ovalue,$args);
	
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