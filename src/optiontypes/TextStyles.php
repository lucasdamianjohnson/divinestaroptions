<?php
/**
* Handles text syltes option types.
*
* Handles the text styles option such as font, size, colors, and drop shadow. 
* 
* @category   Options
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha .2
*/
class TextStyles extends Option
{
	

	public function get_value_structure($type,$args,$mode = null) : array
	{
		return array();
	}


	public function get_data_strcutre($type,$args,$mode = null) : array
	{
		return array();
	}


	public function get_html($type,$option,$value) : string
	{
		return '';
	}


	public function is_type($type) : bool
	{
		return false;
	}

}