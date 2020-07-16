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




		if($type == 'sortablelist') {
		  $data  = $option->so->o;
		}
		if($type == 'doublesortablelist') {
		  $data  = $option->default->o;
		}
		$newvalue = array();
		//$newvalue = implode(",",$option->so->o);
		foreach ($data as $key => $value) {
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


	public function get_html($type,$option,$value,$args=null) : string
	{
		if(isset($option['mode'])) {
  			$mode = $option['mode'];
  		} else {
  			$mode = '';
  		}



  		switch ($type) {
  			case 'sortablelist':
  				$return = $this->sortable_list($option,$value);
  				break;
  			case 'doublesortablelist':
  				$return = $this->double_sortable_list($option,$value);
  				break;
  			default:
  				$return = $this->return_form_error('No known option type or mode for option type DragAndDrop.');
  				break;
  		}

		return $return;
	}

	public function is_type($type) : bool
	{
		$types = array(
			'sortablelist',
			'doublesortablelist'
		);
		return array_search($type, $types ) !== false;
	}




	private function double_sortable_list($option,$value,$mode = null) : string
	{
		$label = $option->label;
		$name = (string) $option->name;
		$description = $option->description;
		$id = $option->value . '-id';
		$did = $option->name . '-description';
		$form_name = "divinestaroptions[doublesortablelist][$name]";

		$tags = array(
		'left-remove',
		'right-remove',
		'left-expand',
		'right-expand',
		'left-swap',
		'right-swap',
		'left-multidrag',
		'right-multidrag',
		'left-put',
		'right-put',
		'left-pull',
		'right-pull',
		'left-sort',
		'right-sort',
		'left-max',
		'left-min',
		'right-min',
		'right-max',
		'left-input',
		'right-input'
		);

		$dt = array();
		foreach ($tags as $tag) {
			if(isset($option[$tag]) && $option[$tag] != '') {
				$dt[$tag] = $option[$tag];
			} else {
				$dt[$tag] = 'false';
			}	
		}


	    $left_list_tags = <<<TAGS
data-for="{$form_name}" data-remove="{$dt['left-remove']}" data-expand="{$dt['left-expand']}" data-swap="{$dt['left-swap']}" data-multidrag="{$dt['left-multidrag']}" data-put="{$dt['left-put']}" data-pull="{$dt['left-pull']}" data-sort="{$dt['left-sort']}" data-max="{$dt['left-max']}" data-min="{$dt['left-min']}" data-input="{$dt['left-input']}" data-list-align="left"
TAGS;
	    $right_list_tags = <<<TAGS
data-for="{$form_name}" data-remove="{$dt['right-remove']}" data-expand="{$dt['right-expand']}" data-swap="{$dt['right-swap']}" data-multidrag="{$dt['right-multidrag']}" data-put="{$dt['right-put']}" data-pull="{$dt['right-pull']}" data-sort="{$dt['right-sort']}" data-max="{$dt['right-max']}" data-min="{$dt['right-min']}" data-input="{$dt['right-input']}"
	data-list-align="right"
TAGS;

		if($dt['right-input'] != 'false') {
		$right_input = <<<HTML
		<input type='hidden' name="{$form_name}" id="{$form_name}-right" value="{$value}"/>
HTML;
		} else {
		$right_input = "";
		}
		if($dt['left-input'] != 'false') {
		$left_input = <<<HTML
		<input type='hidden' name="{$form_name}" id="{$form_name}-left" value="{$value}"/>
HTML;
		} else {
		$left_input = "";
		}
		



		$form_data = '';
		$wrap_tags = "tabindex='0'   data-name='$form_name' class='ds-options-sortablelist-group-item'";

		$close_icon = $this->get_form_icon('close-mini');
		$right_args = [
			'dndlist' => true,
			'for' => "$form_name-doublesortablelist-right",
			'add_close' => 	true,
			'close_class' => 'ds-options-remove-sortablelist-item',
			'add_expand' => true,
			'expand_class'=> 'ds-options-expand-sortablelist-item'
		];
	

		$left_args = [
			'dndlist' => true,
			'for' => "$form_name-doublesortablelist-left",
			'add_expand' => true,
			'expand_class'=> 'ds-options-expand-sortablelist-item',
			'include_content' => $option->socontent
		];

	    

		if($value != '') {
	
		$data = explode(",", $value);
		prev($data);
		$form_data = $this->wrap_elemnts(['li','ul'],$wrap_tags,$data,'',$right_args);

	 } else {

	 	$form_data = $this->wrap_elemnts(['li','ul'],$wrap_tags,$option->default,'',$right_args);
	 }

	 	$options = $this->wrap_elemnts(['li','ul'],$wrap_tags,$option->so,'',$left_args);

	 

		$form_html = <<<HTML
		<h3>$label</h3>
		<div class='flex-row ds-options-w100'>

		<div class="flex-col flex-space">
    	$left_input
       <ul {$left_list_tags}  id="{$form_name}-doublesortablelist-left" class="ds-options-w100 ds-options-dnd-list ds-options-doublesortablelist">
         	 $options
         </ul>
        </div>

        <div class="flex-col flex-space">
        $right_input
       <ul {$right_list_tags} id="{$form_name}-doublesortablelist-right" class="ds-options-w100 ds-options-dnd-list">
         	 $form_data
         </ul>
        </div>

         </div>
HTML;

   
        return $this->get_form_wrap($form_html);
	}





    private function sortable_list($option,$value,$mode = null) : string
    {
    	$label = $option->label;
		$name = (string) $option->name;
		$description = $option->description;
		$id = $option->value . '-id';
		$did = $option->name . '-description';


		$form_name = "divinestaroptions[sortablelist][$name]";
		$form_data = '';
		$wrap_tags = "tabindex='0'   data-name='$form_name' class='ds-options-sortablelist-group-item'";
		if($value != '') {
		
		$data = explode(",", $value);
		prev($data);
		$form_data = $this->wrap_elemnts(['li','ul'],$wrap_tags,$data,true);


	 } else {

	 	$form_data = $this->wrap_elemnts(['li','ul'],$wrap_tags,$option);
	 }


		$form_html = <<<HTML
       <input type='hidden' name="{$form_name}" id="{$form_name}" value="{$value}"/>
       <ul id="{$form_name}-sortablelist" class="ds-options-dnd-list ds-options-sortablelist" data-id="1">
         	 $form_data
         </ul>
HTML;

   
        return $this->get_form_wrap($form_html,$label,true,$id);
}











}