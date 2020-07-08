<?php



class DivineStarOptions
{

private $dsbfo;
private $loaded_options;

private function load_options_xml($going_to) {

	return	simplexml_load_file(PLUGIN_PATH.'src/options/xml/'.$going_to.'.xml');
}
private function save_options_xml($going_to,$options) {

 
	file_put_contents(PLUGIN_PATH.'src/options/xml/'.$going_to.'.xml', $options->asXML());	

}
private function load_value_json($json) {

	return json_decode(file_get_contents(PLUGIN_PATH.'src/options/data/'.$json.'.json'),true);
}
private function save_value_json($going_to,$data) {

	file_put_contents(PLUGIN_PATH.'src/options/data/'.$going_to.'.json', json_encode($data));
}



function load_options($options) {
	$this->unset_options();
	$this->loaded_options = $this->load_value_json($options);
	//print_r($this->loaded_options);

	/*
		$dsbfo = array();

				$i = 0;
	$sections = $this->load_options_xml('bookingformoptions.xml');
		foreach($sections->section as $section) {
				foreach($section->option as $key => $option){
					$dsbfo["$option->name"] = "$option->value";

				}
			}
	    $this->dsbfo = $dsbfo;
    */
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



private function text_option($option,$value) {
$name = $option->name;

//$value = $option->value;
$label = $option->label;
$description = $option->description;
$id = $option->value . '-id';
$did = $option->name . '-description';
	return <<<HTML

<tr>
<th scope="row"><label for="{$id}">$label</label></th>
<td><input name="{$name}" type="text" id="{$id}" aria-describedby="{$did}" value="{$value}" class="regular-text">
<p class="description" id="{$did}">$description</p></td>
</tr>
HTML;
}

private function option_form_start($name,$title,$style,$going_to) {
return <<<HTML
<form data-going-to='{$going_to}' class='ds-options-menu-form' data-for='{$name}' id='{$name}-form'method="post" {$style} action="options.php" novalidate="novalidate">
<table class="form-table ds-options-menu-form-table " role="presentation">
<tbody>
<tr><td class='ds-options-menu-form-table-title'><h2 class='title'>$title</h2></td></tr>
HTML;  
}
private function option_form_end($name) {
return <<<HTML
</tbody>
</table>
<p class="submit"><input type="submit" name="submit" id="{$name}-form-submit" class="button button-primary" value="Save Changes"></p>
</form>
HTML;	
}

function get_options_form() {
		$form_html = '';
		$i = 0;
        $section_html = <<<HTML
<table class="form-table ds-options-menu-table" role="presentation">
<tbody>
<tr>
<td class='ds-options-menu-section-td'>
        	<ul class='ds-options-section-list'>
HTML;
   
		$sections = $this->load_options_xml('divinestarbookingoptions');
		foreach($sections->section as $section) {

				$title =  $section['title'];
			    $name =  $section['name'];
			    $going_to = $section['for'];
				if($i == 0) {
					$eclass = 'ds-option-section-expanded active';
					$style = 'style="display:block;"';
				} else {
					$eclass = '';
					$style = 'style="display:none;"';
				}
						
				$form_html .= $this->option_form_start($name,$title,$style,$going_to);		 
				$i++;
				foreach($section->option as $key => $option){
					
				    if($option['type'] == 'text') {
						 $form_html .= $this->text_option($option,$this->get_option((string)$option->name));
						}

				}

				$form_html .= $this->option_form_end($name);	
			$sshtml = '';
			$buttoneclass = '';
			if(isset($section->subsection) && $section->subsection != null) {
			    $buttoneclass = 'ds-section-optoin-has-submenu';
				$sshtml .= <<<HTML
				<ul class='ds-subsection-menu-ul' {$style}>

HTML;
			foreach ($section->subsection as $key => $subsection) {
			    $stitle =  $subsection['title'];
			    $sname =  $subsection['name'];
			    $sgoing_to = $section['for'];
				$form_html .= $this->option_form_start($sname,$stitle,'style="display:none;"',$sgoing_to);	
				$sshtml .= <<<HTML
	<li class='ds-subection-menu-option-li'><button data-id='{$sname}' data-for='{$name}' class='ds-section-menu-option-button '><div class='ds-section-menu-option-text'>$stitle</div></button></li>
HTML;
			 foreach($subsection->option as $key => $soption) {
						if($soption['type'] == 'text') {
						 $form_html .= $this->text_option($soption,$this->get_option((string)$soption->name));
						}
				}
				$form_html .= $this->option_form_end($name);	
			}
			$sshtml .= <<<HTML
				</ul>

HTML;

		}
		      
			    $section_html .= <<<HTML

<li id='{$name}' class='ds-section-menu-option-li '><button data-id='{$name}' class='ds-section-menu-option-button ds-section-menu-option-top-level {$buttoneclass} {$eclass}'><div class='ds-section-menu-option-icon dashicons-before dashicons-star-empty'></div><div class='ds-section-menu-option-text'>$title</div></button>$sshtml</li>
HTML;
	
		


			}
$section_html .= <<<HTML
</ul>
</td>
HTML;	


echo <<<HTML
$section_html
<td class='ds-options-section-form-table'>
$form_html
</td></tr>
</tbody>
</table>



HTML;
	}



function load_into_files() {
$json = array("json_name"=>"generaloptions");
echo 'loading into files!';

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




function save_booking_form_options() {
		$newvalues = array(
array('name'=>'test1','value'=>'what   ever!'),
array('name'=>'test2','value'=>'what ever 2!')

);



			//	$option->name = $newvalues[$key]['name'];
			//	$option->value = $newvalues[$key]['value'];


				$options = $this->load_options_xml('divinestarbookingoptions.xml');
				$i = 0;
				foreach($options->option as $option){
					$option->name = $newvalues[$i]['name'];
				    $option->value = $newvalues[$i]['value'];
				    $i++;

				}


 	    if(is_writable(PLUGIN_PATH.'src/options/divinestarbookingoptions.xml')) {
			$this->save_options_xml('divinestarbookingoptions.xml'); 
		} else {

		}

		

	}


}