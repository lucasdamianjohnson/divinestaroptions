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




    public function get_html($type,$option,$value) : string 
    {
    	if(isset($option['mode'])) {
  			$mode = $option['mode'];
  		} else {
  			$mode = '';
  		}


  
        if($type == 'text') {
  	return $this->text_option($option,$value);
  		}

  	if($type == 'number') {


  	return $this->number_option($option,$value);



  		}

  	if($type == 'checkbox') {
  	return $this->check_box_option($option,$value);
  		}

  	if($type == 'selectdropdown') {

  	return $this->select_dropdown_option($option,$value,$mode);
  		}

  	if($type == 'generic') { return '';}

  	throw new Exception('No known simple type.');


    }



    private function search_dropdown($option,$value) {
    	$label = $option->label;
		$name = (string) $option->name;
		$description = $option->description;
		$id = $option->value . '-id';
		$did = $option->name . '-description';


		$form_name = "divinestaroptions[selectdropdown][$name]";

	    $wrap_tags = "tabindex='0' onclick='clieckedDropDownSearchOption(event,\"$form_name\",defaultCallBack)' class='ds-dropdown-search-item'";
		$form_data = $this->wrap_elemnts(['a','p'],$wrap_tags,$option);




	$html = <<<HTML
<div class='flex-row'>

<div class="flex-col flex-center">
<p class='ds-form-label'>Font Fanily</p>
</div>

<div class="flex-col flex-center">

<input type="hidden" value="" id="{$form_name}" name="{$form_name}"/>
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
<div>
</div>

</div>

</div>


HTML;

		return $this->get_form_wrap($html,$label,true,$id);
    }
  	private function select_dropdown_option($option,$value,$mode) {
		$name = $option->name;
		$title = $option->title;
		$label = $option->label;
		$description = $option->description;
        
		if($mode == "searchable") {
			return $this->search_dropdown($option,$value);
		}


        $did = '';$ds = '';$dad = '';
		if($description != '') {
		$did = $option->name . '-description';
		$ds = <<<HTML
		<p class="description" id="{$did}">$description</p>
HTML;
		$dad = "aria-describedby='$did'";
		}


		$o_html = $this->wrap_elemnts(['option','optgroup'],'',$option,false,$value);


		return <<<HTML
<tr>
<th scope="row"><label for="{$name}-id">$label</label></th>
<td>

<select id="{$name}-id" name="divinestaroptions[selectdropdown][{$name}]"  value="{$value}" {$dad}>
$o_html 
</select>
$ds
</td>

</tr>
HTML;




  	}

	private function number_option($option,$value) {
		$name = $option->name;
		if(!is_numeric($value)) {
			//throw new Exception("The value provided is not numeric for the option $name.");
			//return '';
		}


		
		$label = $option->label;
		$description = $option->description;
		$id = $option->value . '-id';
		$did = $option->name . '-description';
		return <<<HTML

		<tr>
		<th scope="row"><label for="{$id}">$label</label></th>
		<td><input name="divinestaroptions[number][{$name}]" type="number" id="{$id}" aria-describedby="{$did}" value="{$value}" class="regular-text">
		<p class="description" id="{$did}">$description</p></td>
		</tr>
HTML;
	}







	private function check_box_option($option,$value) {
		$name = $option->name;
		$title = $option->title;
		$label = $option->label;
		$description = $option->description;
        
        $did = '';$ds = '';$dad = '';
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

		$id = $option->value . '-id';
		$did = $option->name . '-description';
		return <<<HTML
		<tr>
		<th scope="row">$title</th>
		<td> <fieldset><legend class="screen-reader-text"><span>$title</span></legend><label for="{$name}-id">
		<input name="divinestaroptions[checkbox][{$name}]" {$dad} type="checkbox" id="{$name}-id" value="1" {$checked}>$label</label>
		</fieldset>
		$ds
		</td>
		</tr>
HTML;
	}

	private function text_option($option,$value) {
		$name = $option->name;
		$label = $option->label;
		$description = $option->description;
		$id = $option->name . '-id';
		$did = $option->name . '-description';
		return <<<HTML
<tr>
<th scope="row">
<label for="{$id}">$label</label>
</th>
<td>
<input name="divinestaroptions[text][{$name}]" type="text" id="{$id}" aria-describedby="{$did}" value="{$value}" class="regular-text">
<p class="description" id="{$did}">$description</p>
</td>
</tr>
HTML;
	}






}