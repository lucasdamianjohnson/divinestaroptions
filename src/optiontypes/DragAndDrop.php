<?php
/**
* Handles drag and drop option types. 
*
* Drag and drop option types includes things like sortable lists. 
* 
* @category   Options
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha .2
*/
class DragAndDrop extends Option
{



	public function generate_save_data_structure($type,$save_data,$mode=null) : array
	{

	return array($this->get_value_structure($type,$save_data,$mode)[0]);
	}

	public function load_from_xml($option) : array
	{
		$type = (string) $option['type'];
		$mode = (string) $option['mode'];
		$value = (string) $option->value;

		$newvalue = array();
		//$newvalue = implode(",",$option->so->o);
		foreach ($option->so->o as $key => $value) {
			$data_value = $value['value'];
			$newvalue[] = $data_value;
		}
		$newvalue = implode(",",$newvalue);
		return $this->get_data_strcutre($type,$newvalue,$mode);
	}


	public function get_value_structure($type,$args,$mode = null) : array
	{

		return array($args);
	}

	
	public function get_data_strcutre($type,$args,$mode = null) : array
	{

	  	return  array(
					'value' => $args,
					'type' => $type 
				); 
	}


	public function get_html($type,$option,$value) : string
	{
		if(isset($option['mode'])) {
  			$mode = $option['mode'];
  		} else {
  			$mode = '';
  		}

  		if($type == 'sortablelist') {
  		   return $this->sortable_list($option,$value);

  		}

		return $this->return_form_error('No known option type or mode for option type DragAndDrop.');
	}

	public function is_type($type) : bool
	{
		$types = array(
			'sortablelist'
		);
		return array_search($type, $types ) !== false;
	}






    private function sortable_list($option,$value) {
    	$label = $option->label;
		$name = (string) $option->name;
		$description = $option->description;
		$id = $option->value . '-id';
		$did = $option->name . '-description';


		$form_name = "divinestaroptions[selectdropdown][$name]";
		$form_data = '';

		if($value != '') {
		$wrap_tags = "tabindex='0'   data-name='$form_name' class='ds-options-sortablelist-group-item'";
		$data = explode(",", $value);
		prev($data);
		$form_data = $this->wrap_elemnts(['li','ul'],$wrap_tags,$data,true);


	 } else {

	 	$form_data = $this->wrap_elemnts(['li','ul'],$wrap_tags,$option);
	 }


		$form_html = <<<HTML
       <h2>Sortable List</h2>
       <input type='hidden' name="{$form_name}" id="{$form_name}" value="{$value}"/>
       <ul id="{$form_name}-sortablelist" class="list-group ds-options-sortablelist" data-id="1">
         	 $form_data
         </ul>
HTML;

   
        return $this->get_form_wrap($form_html,$label,true,$id);
}











}