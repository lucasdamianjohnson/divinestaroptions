<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class DivineStarOptions
{

	private $dsbfo;
	private $loaded_options;
	private $simple_time;



	function __construct() {
		//add_action( 'wp_ajax_divine_star_updateoptions', array( $this,'update_options') );
		$this->simple_type  = array("text","number","checkbox","selectdropdown","generic");
	}


	public function testing($test = null) {
		if($test === null) {
			 throw new Exception('The variable is null');
		}
		return 'test';
	}


	public function is_simple_type($type){
		   return array_search($type, $this->simple_type ) !== false;
	}



	private function load_options_xml($going_to) {

		return	simplexml_load_file(OPTIONS_PATH.'xml/'.$going_to.'.xml');
	}

	public function load_options_xml_string($xml) {
		return simplexml_load_string($xml);
    }


	private function load_value_json($json) {

		return json_decode(file_get_contents(OPTIONS_PATH.'data/'.$json.'.json'),true);
	}
	public function save_value_json($going_to,$data) {

		file_put_contents(OPTIONS_PATH.'data/'.$going_to.'.json', json_encode($data));
	}



	public function load_options($options) {
		$this->unset_options();
		$this->loaded_options = $this->load_value_json($options);
	}


	public function get_loaded_options() {

		return $this->loaded_options;
	}



	/**
	* Get the option value of the currently loaded options.
	*
	* @param string $name The name of the value you would like to get.
	*
	* @return array The value of the option FALSE If the option is not found. 
	* @access public
	*/
	public function get_option($name) {
		//return $this->loaded_options;
		if(isset($this->loaded_options[$name]['value']) && $this->loaded_options[$name]['value'] !== null){
			return $this->loaded_options[$name]['value'];
		} else {
			return false;
		}
	}


	public function set_option($name,$value) {

		return $this->loaded_options[$name]['value'] = $value;
	}

	private function unset_options() {
		$this->dsbfo = null;
	}


function get_options_js($options) {
     $json = json_encode($this->load_value_json($options));

	echo <<<HTML
	<script type="text/javascript">
		var ds_options = JSON.parse('{$json}');
	</script>

HTML;

}




	private function get_start_json_data_structure($option) {
			$type = trim((string) $option['type']);
			
			if(array_search($type, $this->simple_type ) !== false) {
				return $this->get_json_data_structure($type,(string) $option['value']); 
			}
		    if($type == 'singleimage') {
		    echo "<h1>singleimage</h1>";
		    $mode = trim((string) $option['mode']);
		   echo "<h1>$mode</h1>";
		        if($mode == "wp") {
		    	return $this->get_json_data_structure($type,[(string) $option['value'],'','','','','','','','',''],$mode);
		        }
		        if($mode == "url") {
		    	return $this->get_json_data_structure($type,[(string) $option['value'],'','','','','',''],$mode);
		        }
		    }

		

	}

     function get_json_data_structure($type,$args,$mode = null) {

    		if(array_search($type, $this->simple_type ) !== false) {
				return  array(
					'value' => $args,
					'type' => $type 
				); 
			}



    }



	function load_into_files($xml) {
		$json = array("json_name"=>"ƒ");

		$sections = $this->load_options_xml($xml);
		$i = 0;

		$ignore_options = array("formhtml","custom_function");
		foreach($sections->section as $section) {

			foreach($section->option as $option){
				$type = (string) $option['type'];
				if(array_search($type, $ignore_options ) !== false) {continue;}
				$json["$option->name"] = $this->get_start_json_data_structure($option);

			}
			foreach($section->subsection as $ssection){

				foreach($ssection->option as $soption){
			        $type = (string) $option['type'];
				    if(array_search($type, $ignore_options ) !== false) {continue;}
					$json["$soption->name"] = $this->get_start_json_data_structure($option);
				}

			}


		}


		$this->save_value_json('generaloptions',$json);
	}








}