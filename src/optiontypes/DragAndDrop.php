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
	private $helper;
	public function set_helper($helper) {
		$this->helper = $helper;
	}


	public function generate_save_data_structure($type,$save_data,$mode=null) : array
	{

		if($type=='doublesortablelist'){

			$listorder = $save_data['listorder'];
			$cids = $save_data['cid'];
			$optiongroups = $save_data['optiongroup'];
			//print_r($optiongroups);
			$newgroups = array();
			$newvalues = array();
			foreach ($optiongroups as $key => $value) {
				foreach($value as $k => $group){

			$newdata = $this->helper->get_options()
					->generate_save_data_structure('optiongroup',$group,'doublesortedlis')[0];
			 echo "\n**************************************\n";
			 echo "$key";
			 echo "\n**************************************\n";

			 $newgroups[$key][$k] = $this->helper->get_options()
 		   	 ->get_data_strcutre('optiongroup',$newdata,$mode);

			}
			//var_dump($newgroups);
		  }
		  echo "\n**************************************\n";
 		   print_r($newgroups);

	  	$return = $this->get_value_structure($type,
	  		[
	  		$listorder,
	  		$cids,
	  		$newgroups
	  		],$mode);
	  	}

	  	if($type=='sortablelist') {
	 	$return = $this->get_value_structure($type,$save_data,$mode);
	  	}

	  	return $return;
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
		$cids = array();
		//$newvalue = implode(",",$option->so->o);
		foreach ($data as $key => $value) {
			$data_value = $value['value'];
			$newvalue[] = $data_value;
			$cids[] = $value['cid'];
		}
		$newvalue = implode(",",$newvalue);
		$cids = implode(",",$cids);

		if($type=='doublesortablelist'){
	  	$return = $this->get_data_strcutre($type,[$newvalue,$cids,['']],$mode);
	  	}

	  	if($type=='sortablelist') {
	 	$return = $this->get_data_strcutre($type,$newvalue,$mode);
	  	}

	   return $return;
	}


	public function get_value_structure($type,$args,$mode = null) : array
	{
			if($type=='doublesortablelist'){
	  	$return = array(
						'listorder' => $args[0],
						'cid' => $args[1],
						'optiongroup' => $args[2]
					);
	  	}

	  	if($type=='sortablelist') {
	  		$return = $args;
	  	}
		return array($return);
	}

	
	public function get_data_strcutre($type,$args,$mode = null) : array
	{


		if($type=='doublesortablelist'){
	  	$return = array(
					'value' => array(
						'listorder' => $args[0],
						'cid' => $args[1],
						'optiongroup' => '',
					),
					'type' => $type 
				); 
	  	}

	  	if($type=='sortablelist') {
	  		$return = array(
	  			'value' => $args,
	  			'type' => $type
	  		);
	  	}
	  	return $return;

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

		$optiongroup = $value['optiongroup'];
		$label = $option->label;

		$cids = $value['cid'];
		$value = $value['listorder'];

		$name = (string) $option->name;
		$description = $option->description;
		$id = $option->value . '-id';
		$did = $option->name . '-description';
		$form_name = "divinestaroptions[doublesortablelist][$name]";

		$tags = array(
		'left-content',
		'right-content',
		'left-remove',
		'right-remove',
		'left-expand',
		'right-expand',
		'left-dynamic-input',
		'right-dynamic-input',
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
data-for="{$form_name}" data-content="{$dt['left-content']}" data-remove="{$dt['left-remove']}" data-expand="{$dt['left-expand']}" data-swap="{$dt['left-swap']}" data-multidrag="{$dt['left-multidrag']}" data-put="{$dt['left-put']}" data-pull="{$dt['left-pull']}" data-sort="{$dt['left-sort']}" data-max="{$dt['left-max']}" data-min="{$dt['left-min']}" data-input="{$dt['left-input']}" data-list-align="left"
	data-dynamic-input="{$dt['left-dynamic-input']}"
TAGS;
	    $right_list_tags = <<<TAGS
data-for="{$form_name}" data-content="{$dt['right-content']}" data-remove="{$dt['right-remove']}" data-expand="{$dt['right-expand']}" data-swap="{$dt['right-swap']}" data-multidrag="{$dt['right-multidrag']}" data-put="{$dt['right-put']}" data-pull="{$dt['right-pull']}" data-sort="{$dt['right-sort']}" data-max="{$dt['right-max']}" data-min="{$dt['right-min']}" data-input="{$dt['right-input']}" data-list-align="right"
	data-dynamic-input="{$dt['right-dynamic-input']}"
TAGS;

		if($dt['right-input'] != 'false') {
		$right_input = <<<HTML
		<input type='hidden' name="{$form_name}[listorder]" id="{$form_name}-right" value="{$value}"/>
		<input type='hidden' name="{$form_name}[cid]" id="{$form_name}-right-cid" value="{$cids}"/>
HTML;
		} else {
		$right_input = "";
		}
		if($dt['left-input'] != 'false') {
		$left_input = <<<HTML
		<input type='hidden' name="{$form_name}[listorder]" id="{$form_name}-left" value="{$value}"/>
		<input type='hidden' name="{$form_name}[cid]" id="{$form_name}-left-cid" value="{$cids}"/>
HTML;
		} else {
		$left_input = "";
		}
		



		$form_data = '';
		$wrap_tags = "tabindex='0'   data-name='$form_name' class='ds-options-sortablelist-group-item'";

		
		$right_args = [
			'dndlist' => true,
			'form_name'=>"$form_name",
			'for' => "$form_name-doublesortablelist-right",
			'add_close' => 	true,
			'close_class' => 'ds-options-remove-sortablelist-item',
			'add_expand' => true,
			'value' => $optiongroup,
			'expand_class'=> 'ds-options-expand-sortablelist-item',
			'include_content' => $option->socontent,
			'loaded_values' => true
		];
		if($dt['right-dynamic-input'] == 'true'){
			$right_args['dynamic-input'] = true;
		}
		if($dt['right-input'] == 'false'){
			$right_args['input'] = false;
		}

		$left_args = [
			'dndlist' => true,
			'form_name'=>"$form_name",
			'for' => "$form_name-doublesortablelist-left",
			'add_expand' => true,
			'value' => $optiongroup,
			'expand_class'=> 'ds-options-expand-sortablelist-item',
			'include_content' => $option->socontent
		];
		if($dt['left-dynamic-input'] == 'true'){
			$left_args['dynamic-input'] = true;
		}
	    if($dt['left-input'] == 'false'){
			$left_args['input'] = false;
		}

		if($value != '') {

		$data = explode(",", $value);
		$cids = explode(",",$cids);
		$data_send = array();
		foreach ($data as $key => $value) {
			$data_send[] = array(
				'value'=> "$value",
				'cid'=> $cids[$key] 
			);
		}
		prev($data);
		$form_data = $this->helper->wrap_elemnts(['li','ul'],$wrap_tags,$data_send,'',$right_args);

	 } else {

	 	$form_data = $this->helper->wrap_elemnts(['li','ul'],$wrap_tags,$option->default,'',$right_args);
	 }

	 	$options = $this->helper->wrap_elemnts(['li','ul'],$wrap_tags,$option->so,'',$left_args);

	 

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

   
        return $this->helper->get_form_wrap($form_html);
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
		$form_data = $this->helper->wrap_elemnts(['li','ul'],$wrap_tags,$data,true);


	 } else {

	 	$form_data = $this->helper->wrap_elemnts(['li','ul'],$wrap_tags,$option);
	 }


		$form_html = <<<HTML
       <input type='hidden' name="{$form_name}" id="{$form_name}" value="{$value}"/>
       <ul id="{$form_name}-sortablelist" class="ds-options-dnd-list ds-options-sortablelist" data-id="1">
         	 $form_data
         </ul>
HTML;

   
        return $this->helper->get_form_wrap($form_html,$label,true,$id);
}











}