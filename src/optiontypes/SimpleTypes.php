<?php
/**
* Simple option types.
*
* Handles option types that just store a string value. 
* This currenlty includes the HTML input types of
* text, number, checkbox, and select.
* @category   Options
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha .2
*/
class SimpleTypes extends Option
{

private $types;
private $simple_types;



		private $helper;
	public function set_helper($helper) {
		$this->helper = $helper;
	}
	public function generate_save_data_structure($type,$save_data,$mode=null) : array
	{

	return array($this->get_value_structure($type,$save_data,$mode)[0]);
	}

	public function __construct() 
	{

		$this->simple_type  = array("text","number","checkbox","selectdropdown");
	}


	public function load_from_xml($option) : array
	{
		$type = (string) $option['type'];
		$mode = (string) $option['mode'];
		$value = (string) $option->value;
		return $this->get_data_strcutre($type,$value,$mode);
	}


public function get_value_structure($type,$args,$mode = null) : array 
{

	if(is_array($args)) {
	  //possible error;
	}

	return array($args);
}



 public function  get_data_strcutre($type,$args,$mode = null) : array 
 {
    	return  array(
					'value' => $args,
					'type' => $type 
				); 
  }
	

  public function is_type($type) : bool 
  {
  	return array_search($type, $this->simple_type ) !== false;
  }




    public function get_html($type,$option,$value,$args=null) : string 
    {

    	if(isset($option['mode'])) {
  			$mode = $option['mode'];
  		} else {
  			$mode = '';
  		}

  		$return = '';
  	
  		switch ($type) {
  			case 'checkbox':
  				$return = $this->check_box_option($option,$value,$mode,$args);
  				break;
  			case 'number':
  				$return = $this->number_option($option,$value,$mode,$args);
  				break;
  			case 'text':
  				$return = $this->text_option($option,$value,$mode,$args);
  				break;
  			case 'selectdropdown':
  				$return = $this->select_dropdown_option($option,$value,$mode,$args);
  				break;
  			default:
  				$return = $this->return_form_error('No known option type or mode for option type DragAndDrop.');
  				break;
  		}


  		return $return;
    }



    private function search_dropdown($option,$value,$mode,$args=null) {
    	$label = $option->label;
		$name = (string) $option->name;
		$description = $option->description;


		$form_data = $this->helper->get_from_element_data('text',$name,$args);
		$form_name = $form_data['form_name'];
		$extra_tags = $form_data['extra_tags'];
		$id = $form_data['id'];
		$extra_tags = $form_data['extra_tags'];
		$did = $form_data['description-id'];



	    $wrap_tags = "tabindex='0' onclick='clieckedDropDownSearchOption(event,\"$form_name\",defaultCallBack)' class='ds-dropdown-search-item'";
		$form_data = $this->helper->wrap_elemnts(['a','p'],$wrap_tags,$option->so);




	$html = <<<HTML



	<input type="hidden" value="" id="{$form_name}" {$extra_tags} name="{$form_name}"/>
	<div tabindex="0" class="dropdown">
	<div class='ds-dropdown-items dropbtn'>

		<div tabindex="0" class="ds-options-dropdownsearch-currentselected" onclick="dropDownSearchClick(event,'{$form_name}')">
		<span id='{$form_name}-dropdownsearch-currentselected'>$value</span>
		</div>

		<div tabindex="0" id='{$form_name}-dropdownsearch-clearcurrentselected' class='ds-options-dropdownsearch-clearselect'>
		<span  onclick='dropDownSearchClearSelect(event,"{$form_name}")' class="ds-close-image"></span>
		</div>

		<div tabindex="0" id='{$form_name}-dropdownsearch-dropdownbutton' class='ds-options-dropdownsearch-dropdownbutton'>
		<span  onclick='dropDownSearchClick(event,"{$form_name}")' class="ds-arrowdown-image"></span>
		</div>

	</div>
	</div>
	  <div id="{$form_name}-dropdownsearch-dropdown" class="dropdown-content">
	    <input class='ds-options-dropdownsearch-searchinput' type="text" placeholder="Search.." id="{$form_name}-dropdownsearch-searchinput" onkeyup="filterFunction('{$form_name}')">
	    <div class='search-list-container'>
	    $form_data
	    </div>
	  </div>



HTML;

    if(isset($args['nested']) && $args['nested']) {
	 		$html = <<<HTML
			<div class='flex-row'>
				<div class="flex-col flex-center">
					<label for="{$id}">$label</label>
				</div>
				<div class="flex-col flex-center">
					$html
				</div>    

			</div>    	
HTML;
	    	return $html;
	    } else {
	    		$this->helper->debug_message($label);
	    	return 	$this->helper->get_form_wrap($html,$label,true,$id);
	    }
    }
  	private function select_dropdown_option($option,$value,$mode,$args=null) {

		if($mode == "searchable") {
			return $this->search_dropdown($option,$value,$mode,$args);
		}
		$name = $option->name;
		$title = $option->title;
		$label = $option->label;
		$description = $option->description;
        
		$form_data = $this->helper->get_from_element_data('text',$name,$args);
		$form_name = $form_data['form_name'];
		$extra_tags = $form_data['extra_tags'];
		$id = $form_data['id'];
		$extra_tags = $form_data['extra_tags'];
		$did = $form_data['description-id'];



        $did = '';$ds = '';$dad = '';
		if($description != '') {
		$did = $option->name . '-description';
		$ds = <<<HTML
		<p class="description" id="{$did}">$description</p>
HTML;
		$dad = "aria-describedby='$did'";
		}


		$o_html = $this->helper->wrap_elemnts(['option','optgroup'],'',$option->so,$value);


		$html = <<<HTML
<select id="{$id}" {$extra_tags} name="{$form_name}"  value="{$value}" {$dad}>
$o_html 
</select>
$ds
HTML;
	
    if(isset($args['nested']) && $args['nested']) {
	 		$html = <<<HTML
	    	<label for="{$id}">$label
	    		$html
	    	</label>
HTML;
	    	return $html;
	    } else {
	    	return 	$this->helper->get_form_wrap($html,$label,true,$id);
	    }


  	}

	private function number_option($option,$value,$mode,$args=null) {
		$name = $option->name;
		if(!is_numeric($value)) {
	
		}
		$label = $option->label;
		$description = $option->description;


		$form_data = $this->helper->get_from_element_data('text',$name,$args);
		$form_name = $form_data['form_name'];
		$extra_tags = $form_data['extra_tags'];
		$id = $form_data['id'];
		$extra_tags = $form_data['extra_tags'];
		$did = $form_data['description-id'];
		
		$html =  <<<HTML

	<input {$extra_tags} name="{$form_name}" type="number" id="{$id}" aria-describedby="{$did}" value="{$value}" class="regular-text">
		<p class="description" id="{$did}">$description</p></td>

HTML;



	    if(isset($args['nested']) && $args['nested']) {
	 		$html = <<<HTML
	    	<label for="{$id}">$label
	    		$html
	    	</label>
HTML;
	    	return $html;
	    } else {
	    	return 	$this->helper->get_form_wrap($html,$label,true,$id);
	    }


	}







	private function check_box_option($option,$value,$mode,$args=null) {
		$name = $option->name;
		$title = $option->title;
		$label = $option->label;
		$description = $option->description;
        
		$form_data = $this->helper->get_from_element_data('text',$name,$args);
		$form_name = $form_data['form_name'];
		$extra_tags = $form_data['extra_tags'];
		$id = $form_data['id'];
		$extra_tags = $form_data['extra_tags'];
		$did = $form_data['description-id'];
		
	


        $ds = '';$dad = '';
		if($description != '') {
		$did = $option->name . '-description';
		$ds = <<<HTML
		<p class="description" id="{$did}">$description</p>
HTML;
		$dad = "aria-describedby='$did'";
		}
        $checked = '';
		if($value != ''){
		$checked = 'checked=""';
		}

		

		$html = <<<HTML

	    <fieldset>
	    <legend class="screen-reader-text"><span>$title</span></legend>
	    <label for="{$id}">
		<input {$extra_tags} name="{$form_name}" {$dad} type="checkbox" id="{$id}" value="1" {$checked}>$label</label>
		</fieldset>
		$ds
	
HTML;



	    if(isset($args['nested']) && $args['nested']) {
	 
	    	return $html;
	    } else {
	    	return 	$this->helper->get_form_wrap($html,$title,true,$id);
	    }


	}

	private function text_option($option,$value,$mode,$args=null) {
		$name = $option->name;
		$label = $option->label;
		$description = $option->description;

		$form_data = $this->helper->get_from_element_data('text',$name,$args);
		$form_name = $form_data['form_name'];
		$extra_tags = $form_data['extra_tags'];
		$id = $form_data['id'];
		$extra_tags = $form_data['extra_tags'];
		$did = $form_data['description-id'];
		


	    $html  = <<<HTML
<input name="{$form_name}" $extra_tags type="text" id="{$id}" aria-describedby="{$did}" value="{$value}" class="regular-text">
<p class="description" id="{$did}">$description</p>
HTML;

	    if(isset($args['nested']) && $args['nested']) {
	    	$html = <<<HTML
	    	<label for="{$id}">$label
	    		$html
	    	</label>
HTML;
	    	return $html;
	    } else {
	    	return 	$this->helper->get_form_wrap($html,$label,true,$id);
	    }


	}






}