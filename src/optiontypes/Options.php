<?php 
/**
* Manage all options. 
*
* This class handles all option types. 
* 
* @category   Options
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha .2
*/
final class Options extends Option
{


private $option_types;
	   public function __construct() {
	   	$this->option_types =array(
      'SimpleTypes' => new SimpleTypes,
      'TextStyles' => new TextStyles,
		  'Content' => new Content,
	   	'Images' => new Images,
	    'Generic' => new Generic);

	   }


	public function add_option_type($option_class_name) {

		$this->option_types[$option_class_name] = new $option_class_name;
	}

	public function get_option($type)  {
		return $this->option_types[$type];
	}

	public function get_value_structure($type,$args,$mode = null) : array {

   	 foreach ($this->option_types as $key => $optype) {
    	 	  if($optype->is_type($type)) {
    	return $optype->get_value_structure($type,$args,$mode);
      }
         }

      return array();
}

       
    public function get_data_strcutre($type,$args,$mode = null) : array {

       foreach ($this->option_types as $key => $optype) {
          if($optype->is_type($type)) {
      return $optype->get_data_strcutre($type,$args,$mode);
      }
         }



      return array(false);
    }

	public  function is_type($type) : bool {

		foreach ($this->option_types as $key => $optype) {
    	 	  if($optype->is_type($type)) {
   			  	return array($key,$type);
      			}
         }


      return array("Unknown",$type);
	}




	  public function get_html($type,$option,$value) : string{
  	  $type = (string) $option['type'];


  	  foreach ($this->option_types as $key => $optype) {
  	  	 if($optype->is_type($type)) {
    	return $optype->get_html($type,$option,$value);
      }
  	  }



  	return '';
    
  }

}