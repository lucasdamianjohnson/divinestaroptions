<?php 
include('Option.php');

include('Content.php');
include('SimpleTypes.php');
include('Images.php');
include('Generic.php');
/*
Manages all option types. 
*/
class Options
{

private $simple;
private $content;
private $images;
private $generic;

	   public function __construct() {
	   	$this->simple = new SimpleTypes;
	   	$this->content = new Content;
	   	$this->images = new Images;
	   	$this->generic = new Generic;
	   }


	public function get_option($type)  {

	  if($type == 'SimpleType') {
    	return $this->simple;
      }

       if($type == 'Content') {
    	return $this->content;
      }

      if($type == 'Images') {
    	return $this->images;
      }

      if($type == 'Generic') {
    	return $this->generic;
      }
	}

	public function get_value_structure($type,$args,$mode = null) : array {

   if($this->simple->is_type($type)) {
    	return $this->simple->get_value_structure($type,$args,$mode);
      }

       if($this->images->is_type($type)) {
    	return $this->images->get_value_structure($type,$args,$mode);
      }

      if($this->content->is_type($type)) {
    	return $this->content->get_value_structure($type,$args,$mode);
      }

      if($this->generic->is_type($type)) {
    	return $this->generic->get_value_structure($type,$args,$mode);
      }

      return array();
}



	public function get_type($type) : array {

	  if($this->simple->is_type($type)) {
    	return array("SimpleType",$type);
      }

       if($this->images->is_type($type)) {
    	return array("Images",$type);
      }

      if($this->content->is_type($type)) {
    	return array("Content",$type);
      }

      if($this->generic->is_type($type)) {
    	return array("Generic",$type);
      }
      return array("Unknown",$type);
	}
	   
    public function get_data_strcutre($type,$args,$mode = null) : array {

      if($this->simple->is_type($type)) {
    	return $this->simple->get_data_strcutre($type,$args,$mode);
      }

       if($this->images->is_type($type)) {
    	return $this->images->get_data_strcutre($type,$args,$mode);
      }

      if($this->content->is_type($type)) {
    	return $this->content->get_data_strcutre($type,$args,$mode);
      }

      if($this->generic->is_type($type)) {
    	return $this->generic->get_data_strcutre($type,$args,$mode);
      }


      return array(false);
    }



	  public function get_html($type,$option,$value) : string {
  	  $type = (string) $option['type'];


  	   if($this->simple->is_type($type)) {
    	return $this->simple->get_html($type,$option,$value);
      }

       if($this->images->is_type($type)) {
    	return $this->images->get_html($type,$option,$value);
      }

      if($this->content->is_type($type)) {
    	return $this->content->get_html($type,$option,$value);
      }

      if($this->generic->is_type($type)) {
    	return $this->generic->get_html($type,$option,$value);
      }

  	return '';
    
  }

}