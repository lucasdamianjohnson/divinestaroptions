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
	abstract public function get_html($type,$option,$value) : string;

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
	* @return string The wraped HTML content.
	* @access protected
	*/

	protected function wrap_elemnts($wrap_in,$wrap_tags,$data,$array = false,$value='') : string
	{
		if(!is_array($wrap_in)) {
			throw new Exception('Error: $wrap_in must be an array for Option wrap_element()');
		}

		$wrap_tag = $wrap_in[0];
		$wrap_group_tg = $wrap_in[1];
		$return_html = '';

		if($array) {
			foreach ($data as $key => $datavalue) {
			$return_html .= <<<HTML
			<$wrap_tag $wrap_tags data-value='{$datavalue}'>$datavalue</$wrap_tag>
HTML;
			}


		} else {


		foreach ($data->so->sog as $sog) {
			$glabel = $sog['label'];
			$return_html .= <<<HTML
			<$wrap_group_tg label='{$glabel}'>
HTML;

			foreach($sog->o as $o ) {
			$ot = (string) $o;
			$ovalue = '';
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
			$return_html .= <<<HTML
			<$wrap_tag {$selected} $wrap_tags $ovalue>$ot</$wrap_tag>
HTML;
			}	

			$return_html .= <<<HTML
			</$wrap_group_tg>
HTML;	
		}

		foreach ($data->so->o as $o) {
		    $ot = (string) $o;
			$ovalue = '';
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
			$return_html .= <<<HTML
			<$wrap_tag {$selected} $wrap_tags value='{$ov}' data-value='{$ov}'>$ot</$wrap_tag>
HTML;
		}


		}

		return $return_html;
	} 


}