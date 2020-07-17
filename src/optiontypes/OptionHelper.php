<?php
/**
* This has helpful functions for options. 
*
* 
* @category   Options
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha .2
*/
class OptionHelper 
{

private $options;
private $dso;

	public function set_options($options)
	{
		$this->options = $options;
	}
	public function set_dso($dso)
	{
		$this->dso = $dso;
	}

	public function get_options(){

		return $this->options;
	}

	public function __construct() 
	{
		//$this->options = new Options();
	}


	public function debug_message($message) {


		echo <<<HTML
<div style='border:5px solid purple;font-size: 20px; background-color: black; color: purple;'>
<code>
$message
</code>
</div>
HTML;
	}
	/**
	* Output an error to the Options Form.
	*
	* @param string $message The error message to display.
	* @return string The error message HTML. 
	* @access public
	*/
	public function return_form_error($message) : string 
	{
		$error = <<<HTML
	<span class='ds-options-form-error'>$message</span>
HTML;
	    return $this->get_form_wrap($error);
	}

	/**
	* Wrap HTML content for Options form. 
	*
	* @param string $content The HTML content.
	* @return string The wraped HTML content.
	* @access public
	*/
	public function get_form_wrap($content,$title = '',$label=false,$id='') : string 
	{
		if($label) {
			$label = <<<HTML
			<label for="{$id}">$title</label>
HTML;
		} else {
			$label = $title;
		}
		if($title != '') {
			$title = <<<HTML
			<th scope="row">$label</th>
HTML;
		}
		return <<<HTML
		<tr>$title<td>$content</td></tr>
HTML;
	}






	public function get_from_element_data($type,$name,$args=null) : array
	{
		$extra_tags = '';
		$form_name = $name;
		$id = "$name-id";
		$did = "$name-description";
		if(!isset($args['input']) || $args['input'] ){

			if(isset($args['optiongroup']) && $args['optiongroup']) {
			
			$group_name = $args['groupname'];
			$form_name = "divinestaroptions[optiongroup][$group_name][$type][$name]";

			} else {

			$form_name = "divinestaroptions[text][$name]";

	    }


	    if(isset($args['optiongroup']) && $args['optiongroup']) {
    			$group_name = $args['groupname'];
    		 	$extra_tags .= " data-group='$group_name' data-option-type='text' data-name='{$name}' ";
    	}
		if(isset($args['form_name']) && $args['form_name']) {

				$data_form_name = $args['form_name'];
				$extra_tags .= " data-for='$data_form_name'";
				if(isset($args['contentindex'])) {
				$ci = $args['contentindex'];
				$form_name =  $data_form_name."[optiongroup][$ci][$group_name][$type][$name]";
			
				} else {
				$form_name =  $data_form_name."[optiongroup][$group_name][$type][$name]";
				}
				$id = $form_name ."-id";	 	

    	}

	    } else {

	    	$extra_tags .= " disabled ";
	    	if(isset($args['optiongroup']) && $args['optiongroup']) {
	    			$group_name = $args['groupname'];
	    		 	$extra_tags .= " data-group='$group_name' data-option-type='$type' data-name='{$name}' ";
	    	}
			if(isset($args['form_name']) && $args['form_name']) {
	    			$data_form_name = $args['form_name'];
	    		 	$extra_tags .= " data-for='$data_form_name' ";
	    	}
	    }





	    return array(
	    	'id'=>$id,
	    	'description-id'=>$did,
	    	'form_name'=>$form_name,
	    	'extra_tags'=>$extra_tags
	    );

	}



	/**
	* Wrap some HTML content in a HTML tag
	*
	* This is used for list option types. 
	*
	* @param array $wrap_in Array of string. The first index being the main wrap tag and 
	* others must the group tags.
	* Ex: ['option','optgroup']
	* @param string $wrap_tags The HTML tags for that container.
	* @param array $data The data to wrap in array form. 
	* XML object the default data to wrap from the form.
	* @param bool $array If the data being wraped is an array
	* @param string $value Use this to set an active option. 
	* @param array $args Other options to wrap elements. 
	* @return string The wraped HTML content.
	* @access public
	*/
	public function wrap_elemnts($wrap_in,$wrap_tags,$data,$value='',$args=null) : string
	{
		if(!is_array($wrap_in)) {
			throw new Exception('Error: $wrap_in must be an array for Option wrap_element()');
		}


		if(is_object($data)) {

		}
		$array = false;
		if(is_array($data)) {
			$array = true;
		}
		$cont = false;
		$content = array();
		if(isset($args['include_content'])) {
		$content = $this->get_included_content($args);
	

			if(count($content) >= 1){
				$cont = true;
			}
	    }

		$wrap_tag = $wrap_in[0];
		$wrap_group_tg = $wrap_in[1];
		$return_html = '';

		if($array) {
			$i = 0;
			foreach ($data as $key => $datavalue) {
				if($cont){
				  $cid = $datavalue['cid'];
				  $datavalue = $datavalue['value'];
				} else {
				  $cid = false;
				}


				$include_html = $this->get_included_html($datavalue,$cid,$content,$i,$args);
				$include_tags = $this->get_included_tags($datavalue,$i,$cid,$args);
				$return_html .= <<<HTML
			<$wrap_tag $include_tags $wrap_tags data-value='{$datavalue}'>$include_html</$wrap_tag>
HTML;
			$i++;
			}


		} else {

		$i = 0;
		foreach ($data->sog as $sog) {
			$glabel = $sog['label'];
			$return_html .= <<<HTML
			<$wrap_group_tg label='{$glabel}'>
HTML;

			foreach($sog->o as $o ) {
			$ot = (string) trim($o);
			$ovalue = '';

			if($cont){
			  $cid = $o['cid'];
			} else {
			  $cid = false;
			}

			if(isset($o['value'])) {
				$ov = $o['value'];	
				$ovalue = "value='$ov'";
			} else {
				$ov = $ot;
				$ovalue = "data-value='$ov'";
			}
			
			if($ov == $value) {
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}

			$include_html = $this->get_included_html($ot,$cid,$content,$i,$args);
			$include_tags = $this->get_included_tags($ot,$i,$cid,$args);

			$return_html .= <<<HTML
			<$wrap_tag $include_tags {$selected} $wrap_tags $ovalue>$include_html</$wrap_tag>
HTML;


			 $i++;
			}	

			$return_html .= <<<HTML
			</$wrap_group_tg>
HTML;	
		}

		foreach ($data->o as $o) {
		    $ot = (string) trim($o);
			$ovalue = '';

			if($cont){
			  $cid = $o['cid'];
			} else {
			  $cid = false;
			}

			if(isset($o['value'])) {
				$ov = $o['value'];	
				$ovalue = "value='$ov'";
			} else {
				$ov = $ot;
				$ovalue = "data-value='$ov'";
			}

			if($ov == $value) {
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}


			$include_html = $this->get_included_html($ot,$cid,$content,$i,$args);
			$include_tags = $this->get_included_tags($ot,$i,$cid,$args);

			$return_html .= <<<HTML
			<$wrap_tag $include_tags {$selected} $wrap_tags value='{$ov}' data-value='{$ov}'>$include_html</$wrap_tag>
HTML;

			$i++;
		}


		}

		return $return_html;
	} 

	/**
	* Get included content from the args provided. 
	*
	*
	* @param array $args Array of args from the wrap function.
	* @return string Either an array of string or an array of data 
	* to get option html data later on. 
	* @access private
	*/
	private function get_included_content($args){
			$htmlargs = array();
			$content = array();
			$loaded_values = false;
			if(isset($args['loaded_values']) && $args['loaded_values']) {
				$loaded_values= true;
			}
			if(isset($args['form_name']) && $args['form_name']) {
			$htmlargs['form_name'] = $args['form_name'];
			}

			if(isset($args['input']) && !$args['input']) {
			$htmlargs['input'] = false;
			} 


			$contents = $args['include_content'];
			$ci = 0;

			foreach ($contents->content as $value) {
					$id = $value['cid'];

					$type = $value['type'];
					if($type == 'html'){

					if(!$loaded_values){
					$value = (string)$value;
					$content["$id"] = $value;
					} else {
						$content["$id"] = array(
							'type' => 'html',
							'value' => $value
						);
					}

					}

					if($type == 'option') {
			
						foreach($value->option as $option) {

							$optiontype = (string)$option['type'];

							if(isset($option['name'])){
								$name = (string)$option['name'];
							} else{
							$name = (string)$option->name;
						}
							$optionvalue = $this->dso->get_option($name);
							$htmlargs['nested'] = true;

						if(!$loaded_values) {

							$htmlargs['contentindex'] = $ci;
							$content["$id"] = $this->options->get_html($optiontype,$option,$optionvalue,$htmlargs);

							} else {

							$content["$id"] = array(
								'type'=>$optiontype,
								'name'=>$name,
								'option'=>$option,
								'htmlargs'=>$htmlargs,
								);


							}

						}
					


					}

					$ci++;
			}



		return $content;
	}


	private function get_included_tags($text,$id,$cid,$args) {
		$return_tags = '';
		if(isset($args['for'])) {
			$for = $args['for'];
		}

		if(isset($args['add_close'])) {
			
		}
		if(isset($args['dndlist'])) {
			$text = trim($text);
			$return_tags .= " data-id='$id' data-for='$for' data-text='$text' "; 
		}
		if($cid){
			$return_tags .= " data-cid='$cid'";
		}

		return $return_tags;
	}


	private function get_included_html($value,$cid,$content,$id,$args) {
			$return_html = '';
			$for = '';
			if(isset($args['for'])) {
				$for = $args['for'];
			}


			$button_html = '';
			if(isset($args['add_close'])) {
				$class = '';
				if(isset($args['close_class'])){
					$class = $args['close_class'];
				}

			$button_html .= <<<HTML
			<button class='ds-form-remove-sortablelist-item-button' data-id="{$id}" data-mode="" 
			onclick="closeListElement('{$for}','{$id}')" 
			data-for="{$for}" class="">
			<span class="ds-form-icon-close-mini"></span>
			</button>
HTML;
			} 
			if(isset($args['add_expand'])) {
				$class = '';
				if(isset($args['add_class'])){
					$class = $args['add_class'];
				}

			$button_html .= <<<HTML
			<button class='ds-form-expand-sortablelist-item-button' data-id="0" data-mode="" 
			onclick="expandListItem('{$for}','{$id}')" 
			data-for="{$for}" class="">
			<span class="ds-form-icon-arrow-down"></span>
			</button>
HTML;
			} 

		


		


			if($button_html != '') {

			$button_html = <<<HTML
			<div class='ds-form-sortablelist-button-container'>
			{$button_html}
			</div>
HTML;
			}

			if(isset($args['dndlist'])) {


	


			if($cid) {
	
	

			  if(!isset($args['loaded_values']) || !$args['loaded_values']) {
			  	$content = $content["$cid"];

			 } else {
		
			 		$content = $content["$cid"];
			 		if(!is_array($content)){return false;}

					if(isset($args['value']) && $args['value']) {

					$optionvalue = $args['value'];

					}	

					if($content['type']=='html') {
						$content = $content['value'];
					}
					if($content['type']=='optiongroup') {
					if(isset($optionvalue[$id][$content['name']]['value'])){
				
							$optionvalue = $optionvalue[$id][$content['name']]['value'];

						} else {

							$optionvalue = '';

						}
					$content['htmlargs']['contentindex'] = $id;
					$content = $this->options->get_html($content['type'],$content['option'],$optionvalue,$content['htmlargs']);

				    }
			 }



			 $return_html .= <<<HTML
			  <div class='ds-options-list-top-content'>
			  $value $button_html
			  </div>
			  <div class='ds-options-list-bottom-content ds-options-list-item-contracted'>
			  $content
			  </div>
HTML;



			} else {
			  $return_html .= <<<HTML
			   <div class='ds-options-list-top-content ds-options-list-item-contracted'>
			  $value $button_html
			  </div>
HTML;
			}

		} else {
		    $return_html .= $value;
		}

			return $return_html;
	
		}


	








}