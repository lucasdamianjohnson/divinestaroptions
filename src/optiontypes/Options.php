<?php 
require_once('Option.php');

require_once('Content.php');
require_once('SimpleTypes.php');
require_once('Images.php');
require_once('Generic.php');
/*
Manages all option types. 
*/
final class Options
{


private $option_types;
	   public function __construct() {
	   	$this->option_types = 
	   	array('SimpleTypes' => new SimpleTypes,
		'Content' => new Content,
	   	'Images' => new Images,
	    'Generic' => new Generic);

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



	public function get_type($type) : array {

		foreach ($this->option_types as $key => $optype) {
    	 	  if($optype->is_type($type)) {
   			  	return array($key,$type);
      			}
         }


      return array("Unknown",$type);
	}
	   
    public function get_data_strcutre($type,$args,$mode = null) : array {

    	 foreach ($this->option_types as $key => $optype) {
    	 	  if($optype->is_type($type)) {
    	return $optype->get_data_strcutre($type,$args,$mode);
      }
         }



      return array(false);
    }



	  public function get_html($type,$option,$value) : string {
  	  $type = (string) $option['type'];


  	  foreach ($this->option_types as $key => $optype) {
  	  	 if($optype->is_type($type)) {
    	return $optype->get_html($type,$option,$value);
      }
  	  }



  	return '';
    
  }

}