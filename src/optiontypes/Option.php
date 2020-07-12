<?php


abstract class Option 
{

    //abstract public function get_html($type,$option,$value) : string;
	abstract public function get_value_structure($type,$args,$mode = null) : array;

	abstract public function get_data_strcutre($type,$args,$mode = null) : array;

	abstract public function get_html($type,$option,$value) : string;

	abstract public function is_type($type) : bool;
}