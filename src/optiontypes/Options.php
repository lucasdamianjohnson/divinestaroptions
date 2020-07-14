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
      $types = array(
      'SimpleTypes',
      'TextStyles',
      'Content' ,
      'Images',
      'Generic',
      'DragAndDrop'
      );

      foreach ($types as $value) {
         if(class_exists($value)) {
         $this->option_types[$value] = new $value;
         } else {
         throw new Exeption('The required option class of '.$value.' was not found.');
         }
      }



	   }


   public function generate_save_data_structure($type,$save_data,$mode=null) : array
   {

       foreach ($this->option_types as $key => $optype) {
          if($optype->is_type($type)) {
      return $optype->generate_save_data_structure($type,$save_data,$mode);
      }
         }

      return array('false');
   }


   public function load_from_xml($option) : array 
   {
    $type = $option['type'];
         foreach ($this->option_types as $key => $optype) {
          if($optype->is_type($type)) {
      return $optype->load_from_xml($option);
      }
         }

    return array();
   }


	public function add_option_type($option_class_name) {

		$this->option_types[$option_class_name] = new $option_class_name;
	}

	public function get_option($type)  {
		return $this->option_types[$type];
	}

	public function get_value_structure($type,$args,$mode = null) : array 
  {

   	 foreach ($this->option_types as $key => $optype) {
    	 	  if($optype->is_type($type)) {
    	return $optype->get_value_structure($type,$args,$mode);
      }
         }

      return array();
}

       
    public function get_data_strcutre($type,$args,$mode = null) : array 
    {

       foreach ($this->option_types as $key => $optype) {
          if($optype->is_type($type)) {
      return $optype->get_data_strcutre($type,$args,$mode);
      }
         }



      return array(false);
    }

 

	public  function is_type($type) : bool {
    /*
		foreach ($this->option_types as $key => $optype) {
    	 	  if($optype->is_type($type)) {
   			  	return array($key,$type);
      			}
         }*/
      $arg_list = func_get_args();
      $looking_for = $arg_list[1];
      return (bool) $this->option_types[$looking_for]->is_type($type);

      //return array("Unknown",$type);
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