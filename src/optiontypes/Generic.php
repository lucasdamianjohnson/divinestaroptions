<?php 



class Generic extends Option 
{


	public function get_value_structure($type,$args,$mode = null) : array {

	return array();
}



	public function get_data_strcutre($type,$args,$mode = null) : array {

			return  array(
					'value' => $args,
					'type' => $type 
				); 
	}

	public function get_html($type,$option,$value) : string {
		return '';
	}

	public function is_type($type) : bool{
		$types = array(
			'generic'
		);
		return (string)$type === 'generic';
		//return array_search($type, $types ) !== false;
	}

}