<?php 
/**
* Add custom functions to the form.
*
* Allows you to add custom functions to the form. 
* 
* @category   Main
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha 0
*/
class DivineStarOptionsCustomFunctions
{
	
	function first_function($option) {
		return <<<HTML
<tr><td><tr><td><button id='imagebuttontest'>upload image</button><div id='myprefix-preview-image'></div></td></tr></td></tr>
HTML;		
	}


}