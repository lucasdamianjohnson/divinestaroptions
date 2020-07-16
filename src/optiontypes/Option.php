<?php
/**
* Set class structure for option types.
*
* This is a temple class for the option types. 
* 
* @category   Options
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha .2
*/
abstract class Option 
{

	/**
	* Generates the JSON structure with data provided by the options form on submission. 
	*
	* @param string $type The option type.
	* @param string $save_data If the value of the type is a string just pass a string.
	* array $save_data If the value needs more values pass the args in an array.
	* @param string $mode The mode for the option type.
	* @return array An array of the option data from the XML. 
	* @access public
	*/
	abstract public function generate_save_data_structure($type,$save_data,$mode=null) : array;

	/**
	* Get the JSON structure from the XML file for each option type.
	*
	* @param string $option The option XML object 
	* @return array An array of the option data from the XML. 
	* @access public
	*/
	abstract public function load_from_xml($option) : array;

	/**
	* Get the option value of the currently loaded options.
	*
	* @param string $type The option type.
	* @param string $args If the value of the type is a string just pass a string.
	* array $args If the value needs more values pass the args in an array.
	* @param string $mode The mode for the option type.
	* @return array The value data structure for the option type
	* @access public
	*/
	abstract public function get_value_structure($type,$args,$mode = null) : array;

	/**
	* Get the whole data structure for the option type.
	* 
	* This will include the value data structure as well. 
	*
	* @param string $type The option type.
	* @param string $args If the value of the type is a string just pass a string.
	* array $args If the value needs more values pass the args in an array.
	* @param string $mode The mode for the option type.
	* @return array The value data structure for the option type
	* @access public
	*/
	abstract public function get_data_strcutre($type,$args,$mode = null) : array;

	/**
	* Get the HTML output for the options form.
	*
	* @param string $type The option type.
	* @param XMLObject $option The XML object from the options for XML.
	* @param string $value The value for the option.
	* array $value The value may be an array as well. 
	* @return string The html output of the option type.
	* @access public
	*/
	abstract public function get_html($type,$option,$value,$args=null) : string;

	/**
	* Check if the passed option type is handled by that class.
	*
	* @param string $type The option type.
	* @return bool If the type is suppourted by that class.
	* @access public
	*/
	abstract public function is_type($type) : bool;

	/**
	* Output an error to the Options Form.
	*
	* @param string $message The error message to display.
	* @return string The error message HTML. 
	* @access protected
	*/
	protected function return_form_error($message) : string 
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
	* @access protected
	*/
	protected function get_form_wrap($content,$title = '',$label=false,$id='') : string 
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
	* @access protected
	*/

	protected function wrap_elemnts($wrap_in,$wrap_tags,$data,$value='',$args=null) : string
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

		$content = array();
		$cont = false;
		if(isset($args['include_content'])) {

			$contents = $args['include_content'];

			foreach ($contents->content as $value) {
					$id = $value['cid'];

					$type = $value['type'];
					if($type == 'html'){
					$content["$id"] = (string)$value;
					}
			}

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
				} else {
				  $cid = false;
				}


				$include_html = $this->get_included_html($datavalue,$cid,$content,$i,$args);
				$include_tags = $this->get_included_tags($datavalue,$i,$args);
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
			$include_tags = $this->get_included_tags($ot,$i,$args);

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
			$include_tags = $this->get_included_tags($ot,$i,$args);

			$return_html .= <<<HTML
			<$wrap_tag $include_tags {$selected} $wrap_tags value='{$ov}' data-value='{$ov}'>$include_html</$wrap_tag>
HTML;

			$i++;
		}


		}

		return $return_html;
	} 


	protected function get_included_tags($text,$id,$args) {
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


		return $return_tags;
	}

	protected function get_included_html($value,$cid,$content,$id,$args) {
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
			  $content = $content["$cid"];
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


	





	/**
	* Get the icon svg for a form element. 
	*
	* @param string $icon The name of the icon to return.
	* @return string The error message HTML. 
	* @access protected
	*/
	protected function get_form_icon($icon) : string 
	{

	switch ($icon) {
		case 'search':
		    $icon_html = <<<HTML
<svg width="21px" height="20px" viewBox="0 0 21 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g  stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-299.000000, -280.000000)" fill="#000000">
            <g  transform="translate(56.000000, 160.000000)">
                <path d="M264,138.586 L262.5153,140 L258.06015,135.758 L259.54485,134.343 L264,138.586 Z M251.4,134 C247.9266,134 245.1,131.309 245.1,128 C245.1,124.692 247.9266,122 251.4,122 C254.8734,122 257.7,124.692 257.7,128 C257.7,131.309 254.8734,134 251.4,134 L251.4,134 Z M251.4,120 C246.7611,120 243,123.582 243,128 C243,132.418 246.7611,136 251.4,136 C256.0389,136 259.8,132.418 259.8,128 C259.8,123.582 256.0389,120 251.4,120 L251.4,120 Z">
                </path>
            </g>
        </g>
    </g>
</svg>
HTML;
			break;
		case 'close':
		    $icon_html = <<<HTML
<svg width="21px" height="20px" viewBox="0 0 21 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
    <g transform="translate(-419.000000, -240.000000)" fill="#000000">
        <g id="icons" transform="translate(56.000000, 160.000000)">
            <polygon points="375.0183 90 384 98.554 382.48065 100 373.5 91.446 364.5183 100 363 98.554 371.98065 90 363 81.446 364.5183 80 373.5 88.554 382.48065 80 384 81.446"></polygon>
        </g>
    </g>
</g>
</svg>
HTML;
			break;
		case 'close-mini':
		$icon_html = <<<HTML
<svg width="8px" height="7px" viewBox="0 0 8 7" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g  stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g  transform="translate(-385.000000, -206.000000)" fill="#000000">
            <g  transform="translate(56.000000, 160.000000)">
                <polygon  points="334.6 49.5 337 51.6 335.4 53 333 50.9 330.6 53 329 51.6 331.4 49.5 329 47.4 330.6 46 333 48.1 335.4 46 337 47.4"></polygon>
            </g>
        </g>
    </g>
</svg>
HTML;
			break;
		case 'arrow-up':
		$icon_html = <<<HTML
<svg width="20px" height="11px" viewBox="0 0 20 11" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g  stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g  transform="translate(-260.000000, -6684.000000)" fill="#000000">
            <g transform="translate(56.000000, 160.000000)">
                <path d="M223.707692,6534.63378 L223.707692,6534.63378 C224.097436,6534.22888 224.097436,6533.57338 223.707692,6533.16951 L215.444127,6524.60657 C214.66364,6523.79781 213.397472,6523.79781 212.616986,6524.60657 L204.29246,6533.23165 C203.906714,6533.6324 203.901717,6534.27962 204.282467,6534.68555 C204.671211,6535.10081 205.31179,6535.10495 205.70653,6534.69695 L213.323521,6526.80297 C213.714264,6526.39807 214.346848,6526.39807 214.737591,6526.80297 L222.294621,6534.63378 C222.684365,6535.03868 223.317949,6535.03868 223.707692,6534.63378"></path>
            </g>
        </g>
    </g>
</svg>
HTML;
			break;
		case 'arrow-down':
		$icon_html = <<<HTML
<svg width="20px" height="11px" viewBox="0 0 20 11" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g  stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g transform="translate(-180.000000, -6684.000000)" fill="#000000">
            <g id="icons" transform="translate(56.000000, 160.000000)">
                <path d="M144,6525.39 L142.594,6524 L133.987,6532.261 L133.069,6531.38 L133.074,6531.385 L125.427,6524.045 L124,6525.414 C126.113,6527.443 132.014,6533.107 133.987,6535 C135.453,6533.594 134.024,6534.965 144,6525.39">
                </path>
            </g>
        </g>
    </g>
</svg>
HTML;
			break;
		default:
			$icon_html = "";
			break;
	}



	    return $icon_html;
	}






}