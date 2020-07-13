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


}