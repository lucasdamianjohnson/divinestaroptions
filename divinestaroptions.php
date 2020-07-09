<?php



class DivineStarOptions
{

	private $dsbfo;
	private $loaded_options;

	private function load_options_xml($going_to) {

		return	simplexml_load_file(OPTIONS_PATH.'xml/'.$going_to.'.xml');
	}


	private function load_value_json($json) {

		return json_decode(file_get_contents(OPTIONS_PATH.'data/'.$json.'.json'),true);
	}
	private function save_value_json($going_to,$data) {

		file_put_contents(OPTIONS_PATH.'data/'.$going_to.'.json', json_encode($data));
	}



	function load_options($options) {
		$this->unset_options();
		$this->loaded_options = $this->load_value_json($options);
	}


	private function get_loaded_options() {

		return $this->loaded_options;
	}

	function get_option($name) {
		return $this->loaded_options[$name]['value'];
	}


	private function set_option($name,$value) {
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







	function load_into_files($xml) {
		$json = array("json_name"=>"generaloptions");

		$sections = $this->load_options_xml('divinestarbookingoptions');
		$i = 0;

		foreach($sections->section as $section) {

			foreach($section->option as $option){

				$json["$option->name"] = array('value' => '','type' => (string) $option['type'] ); 

			}
			foreach($section->subsection as $ssection){

				foreach($ssection->option as $soption){
					$json["$soption->name"] = array('value' => '','type' => (string) $soption['type'] ); 
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

		if(isset($_POST['data'])) {
			$data =json_decode( stripslashes($_POST['data']),true);
		} else {
			die();
		}


		$this->load_options($going_to);

		foreach ($data as $key => $value) {
			print_r($value);
			$this->set_option($value['name'],$value['value']);
		}

		$this->save_value_json($going_to,$this->get_loaded_options());



	}







}