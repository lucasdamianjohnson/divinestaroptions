<?php




class Content extends Option 

{

	public function get_value_structure($type,$args,$mode = null) : array {

	return array();
}




    public function get_data_strcutre($type,$args,$mode = null) : array  {



    	return array();
    }


	public function get_html($type,$option,$value) : string {


		return $option->value;
	}


	public function is_type($type) : bool {
	$types = array(
		'formhtml'
	);

	 return array_search($type, $types ) !== false;
	}



}





?>