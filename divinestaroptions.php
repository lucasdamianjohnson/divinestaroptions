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
		add_action( 'wp_ajax_divine_star_updateoptions', array( $this,'update_options') );
		$this->simple_type  = array("text","number","checkbox","selectdropdown","generic");
	}



	private function load_options_xml($going_to) {

		return	simplexml_load_file(OPTIONS_PATH.'xml/'.$going_to.'.xml');
	}


	private function load_value_json($json) {

		return json_decode(file_get_contents(OPTIONS_PATH.'data/'.$json.'.json'),true);
	}
	private function save_value_json($going_to,$data) {

		file_put_contents(OPTIONS_PATH.'data/'.$going_to.'.json', json_encode($data));
	}



	public function load_options($options) {
		$this->unset_options();
		$this->loaded_options = $this->load_value_json($options);
	}


	private function get_loaded_options() {

		return $this->loaded_options;
	}

	public function get_option($name) {
		//return $this->loaded_options;
		if(isset($this->loaded_options[$name]['value']) && $this->loaded_options[$name]['value'] !== null){
			return $this->loaded_options[$name]['value'];
		} else {
			return false;
		}
	}


	private function set_option($name,$value) {
	if(array_search($this->loaded_options[$name]['type'], $this->simple_type ) !== false) {
		return $this->loaded_options[$name]['value'] = $value;
	}

	if($this->loaded_options[$name]['type'] == 'singleimage') {
		return $this->loaded_options[$name] = $value;	
	}

		return false;

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


    			if($type == 'singleimage') {



    			if($mode == "wp") {
    				$imgvalue =array( 
						'id' => $args[0],
						'orgsrc' => $args[1],
						'size' => $args[2],
						'src' => $args[3],
						'alt' => $args[4],
						'title'=> $args[5],
						'caption'=>$args[6],
						'description'=>$args[7],
						'orgwidth' =>$args[8],
						'orgheight' => $args[9]
					);
				
    			}
    			if($mode == "url") {
    				$imgvalue =array( 
						'url' => $args[1],
						'alt' => $args[2],
						'title'=> $args[3],
						'caption'=>$args[4],
						'description'=>$args[5],
						'width' =>$args[6],
						'height' => $args[7]
					);
				
    			}
				$data = array( 
					'value' => $imgvalue,
					'type' => $type, 
					'mode' => $mode
				); 
				if($mode == "wp") {
					$data['mode-set'] = 'test';
				}

				return $data;
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




	function update_options() {
	
		if(isset($_POST['going_to'])) {
			$going_to = $_POST['going_to'];
		} else {
			die();
		}
		if(!isset($_POST['divinestaroptions'])) {
			die();
		}


	    $this->load_options($going_to);

		$data = $_POST;
		print_r($data);
	    foreach ($data['divinestaroptions'] as $type => $typedata) {


	    	foreach ($typedata as $key => $value) {
	    			
	    		if(array_search($type, $this->simple_type) !== false) {
	    				echo "<h1>$type</h1>";
	    			$this->set_option($key,$value);
	    		}
	    		if($type == 'singleimage') {
	    			$mode = $value['mode'];
	    			if($mode == "wp") {

	    			$newvalue = $this->get_json_data_structure(
	    				$type,
	    			 	[
	    			 	$value['id'],
	    			 	$value['orgsrc'],
	    			 	$value['size'],
	    			 	$value['src'],
	    			 	$value['alt'],
	    			 	$value['title'],
	    			 	$value['caption'],
	    			 	$value['description'],
	    			 	$value['orgwidth'],
	    			 	$value['orgheight']
	    			 ],
	    			 $mode
	    			);
	    		    }
	    		    if($mode == "url") {

	    			$newvalue = $this->get_json_data_structure(
	    				$type,
	    			 	[
	    			 	$value['url'],
	    			 	$value['alt'],
	    			 	$value['title'],
	    			 	$value['caption'],
	    			 	$value['description'],
	    			 	$value['width'],
	    			 	$value['height']
	    			 ],
	    			 $mode
	    			);
	    		    }
	    			$this->set_option($key,$newvalue);
	    		}



	    	}


	    }

		$this->save_value_json($going_to,$this->get_loaded_options());

	}







}