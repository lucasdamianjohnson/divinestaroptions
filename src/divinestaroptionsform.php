<?php 
include('optiontypes/optionsetup.php');
include('divinestaroptionscustomsfunctions.php');
/**
* Create the options form. 
*
* This creates the options form and handles the updating of options. 
* 
* @category   Main
* @package    DivineStarOptions
* @copyright  Copyright (c) 2020 Divine Star LLC (http://www.divinestarsoftware.org)
* @license    License: GPLv3 or later
* @version    Alpha: .2  
* @since      Class available since Alpha 0
*/
class DivineStarOptionsForm
{
private $dso;
private $dsocf;
private $simple_types;
private $options;

    function __construct() {
    	$this->dsocf = new DivineStarOptionsCustomFunctions;
    	$this->options = new Options;	
    

    	if(function_exists('add_action')) {
        add_action( 'wp_ajax_divine_star_update_image_form', array( $this,'update_image_upload') );
        add_action( 'wp_ajax_divine_star_updateoptions', array( $this,'update_options') );
   		}
    }


    function initialize($dso) {
    	$helper = new OptionHelper();
	    $helper->set_options($this->options);
	    $helper->set_dso($dso);
	    $this->options->set_helper($helper);
	    $this->options->set_group_options($this->options);
		$this->dso = $dso;
	}



	function load_into_files($xml) {
		$json = array("json_name"=>"$xml");

		$sections = $this->load_options_xml($xml);

		foreach($sections->section as $section) {

			foreach($section->option as $option){
		
				if(isset($option['name'])) {
					$name = (string) $option['name'];
				} else {
					$name = (string) $option->name;
				}
				$json[$name] = $this->options->load_from_xml($option);

			}
			foreach($section->subsection as $ssection){

				foreach($ssection->option as $soption){

					if(isset($option['name'])) {
						$name = (string) $option['name'];
					} else {
						$name = (string) $option->name;
					}
					$json[$name] = $this->options->load_from_xml($soption);

				}

			}


		}


		$this->dso->save_value_json('generaloptions',$json);
	}





    function get_options() {

    	return $this->options;
    }

    function add_option_type($option_class_name) {


    	$this->options->add_option_type("CustomOptionType");
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


	    $this->dso->load_options($going_to);
	    
		$data = $_POST;

		print_r($data);

	    foreach ($data['divinestaroptions'] as $type => $typedata) {
	    			
	    	
	    	foreach ($typedata as $key => $value) {
	    			$type = (string) $type;
	    	
					if(isset($value['mode'])) {
					$mode = $value['mode'];
					} else {
					$mode = null;
					}
					$newvalue = $this->options->generate_save_data_structure($type,$value,$mode);
	    			
					$this->dso->set_option($key,$newvalue[0]);
			
	    	}
			
	
	    }
	    
		$this->dso->save_value_json($going_to,$this->dso->get_loaded_options());
		
	}







 function update_image_upload() {
 
	 if(isset($_GET['imgid']) ){
       $id = $_GET['imgid'];
	 } else {
	  wp_send_json_error();
	  die();
	 }
	 if(isset($_GET['size']) ){
       $size = $_GET['size'];
	 } else {
	  wp_send_json_error();
	  die();
	 }
	 $osize = $size;
	 if(count(explode(" ", $size)) > 1) {
	 	$size = explode(" ", $size);
	 }
      

	 $imgdata = $this->wp_get_attachment( $id );
	 $src = wp_get_attachment_image_src( $id,  $size)[0];
	 $orgimage = wp_get_attachment_image_src( $id,  'full');
	 $orgimage_src =  $orgimage[0];
	 $orgimage_width =  $orgimage[1];
	 $orgimage_height =  $orgimage[2];


	 
   	 $data = $this->options->get_value_structure('singleimage',
   			[$id,
   			$orgimage_src,
   			$osize,
   			$src,
   			$imgdata['alt'],
   			$imgdata['title'],
   			$imgdata['caption'],
   			$imgdata['description'],
   			$orgimage_width,
   			$orgimage_height
   		   ],
   		   "wp"
   		);
   	
   
      wp_send_json_success( $data );

    
}


	private function wp_get_attachment( $attachment_id ) {

$attachment = get_post( $attachment_id );
return array(
    'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
    'caption' => $attachment->post_excerpt,
    'description' => $attachment->post_content,
    'href' => get_permalink( $attachment->ID ),
    'src' => $attachment->guid,
    'title' => $attachment->post_title
);
}


    function enqueue_scripts() {
      if(function_exists('wp_enqueue_media')) {
      wp_enqueue_media();
      wp_enqueue_script('dsoption_form_functions',plugins_url('assets/js/functions.js',__FILE__) );
      wp_enqueue_script('dsoption_form_js',plugins_url('assets/js/form.js',__FILE__) );
      wp_enqueue_script('dsoption_formdnd_js',plugins_url('assets/js/formdnd.js',__FILE__) );
      wp_enqueue_script('ds_options_sortable_js',plugins_url('assets/js/sortable/Sortable.min.js',__FILE__) );
      }
     // wp_enqueue_script('dsoption_form_functions',OPTIONS_PATH.'assets/js/functions.js' );
     // wp_enqueue_script('dsoption_form_main',OPTIONS_PATH.'assets/js/form.js' );
    }




	private function load_options_xml($going_to) {

		return	simplexml_load_file(OPTIONS_PATH.'xml/'.$going_to.'.xml');
	}


  private function get_option_html($option) {
  	$type = (string) $option['type'];
  	if($option['type'] == 'custom_function') { 
  		return $this->custom_function_option($option);
  	}

	if(isset($option['name'])){
	$oname = (string) $option['name'];
	} else {
	$oname = (string) $option->name;
	}
  	$value = $this->dso->get_option((string)$oname);
    return $this->options->get_html($type,$option,$value);

   /*
  		if($this->dso->is_simple_type($type)) {
    
    	$value = $this->dso->get_option((string)$option->name);
    	return $this->options->get_html($type,$option,$value);

}


  	if($option['type'] == 'singleimage') {
  		return $this->single_image_option($option,$this->dso->get_option((string)$option->name));
  	} 

  	if($option['type'] == 'formhtml') {
  	return $option->value;
  	}

  	if($option['type'] == 'custom_function') { 
  		return $this->custom_function_option($option);
  	}
*/

    
  }

  


    private function custom_function_option($option) {

    	return call_user_func(array($this->dsocf, (string)$option->function),$option);
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
		$id = substr(md5(time()), 0, 16);
		return <<<HTML
		</tbody>
		</table>
		<p class="submit"><!--<input type="clear" class="button" value="Clear Changes">--><input type="submit"   class="button button-primary" value="Save Changes"></p>
		</form>
HTML;	
	}





	function get_options_form($options) {
		$form_html = '';
		$i = 0;
		$section_html = <<<HTML
		<div id='ds-options-menu-js-id' class="ds-options-menu-wrap ds-menu-open ds-sidebar-wp-open">
		<div class='ds-options-menu'>
		<ul class='ds-options-section-list'>
		
		<li  class='ds-options-menu-button-container'>
			<button data-id='ds-options-menu-button' class='ds-section-menu-button ds-section-menu-option-top-level '>
				<div class='ds-section-menu-option-icon dashicons-before dashicons-menu'></div>
			</button>
			<button data-id='ds-options-close-menu-button' class='ds-section-menu-button ds-section-menu-option-top-level '>
				<div class='ds-section-menu-option-icon dashicons-before dashicons-no'></div>
			</button>
		</li>
HTML;
		
		//$sections = $this->load_options_xml('divinestarbookingoptions');
		$sections = $this->load_options_xml($options);
		foreach($sections->section as $section) {

			$title =  $section['title'];
			$name =  $section['name'];
			$icon = $section['icon'];
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
					
					 $form_html .= $this->get_option_html($option); 
				
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
				
							$form_html .= $this->get_option_html($soption);
					
					}
		
					$form_html .= $this->option_form_end($name);	
				}
				$sshtml .= <<<HTML
				</ul>

HTML;

			}
			
			$section_html .= <<<HTML

			<li id='{$name}' class='ds-section-menu-option-li '><button data-id='{$name}' class='ds-section-menu-option-button ds-section-menu-option-top-level {$buttoneclass} {$eclass}'><div class='ds-section-menu-option-icon dashicons-before {$icon}'></div><div class='ds-section-menu-option-text'>$title</div></button>$sshtml</li>
HTML;
			
			


		}
		$section_html .= <<<HTML
		</ul>
		</div>
HTML;	

        $css = $this->get_css();
        $js = $this->get_javascript();
		echo <<<HTML
		<div class='flex-row'>

		<div class='flex-col'>
		$section_html
	
		</div>

	
		<div class='flex-col'>
		<div class='ds-form-container'>
		$form_html
		</div>
		</div>

		</div>
		</div>
		$css $js
HTML;
	}




private function get_javascript() {
   return <<<HTML
<script type="text/javascript">


</script>


HTML;

}

private function get_css() {
$url = OPTIONS_URL;
	return <<<HTML
<style type="text/css">

ul.ds-options-section-list {
  list-style: none;
}

@media (max-width:961px) {

button[data-id=ds-options-menu-button] {
	display: block;
}
button[data-id=ds-options-close-menu-button] {
	display: none;
}



div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button div.ds-section-menu-option-text {
	position: absolute;
	margin-top: -8px;
	margin-left: 20px;
	font-weight: 600;
}

 div.ds-options-menu-wrap.ds-sidebar-wp-open {
		left: 160px;
		width: 40px;
	
	}
 div.ds-options-menu-wrap.ds-sidebar-wp-collapsed {
		left: 30px;
		width: 40px;

	}

div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button.ds-section-menu-option-top-level div.ds-section-menu-option-text {
	visibility: hidden;

}

div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list {

}

div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu 
ul.ds-subsection-menu-ul
{
  transition: 0.2s 1s; 
  visibility: hidden;
  display: none !important;
}
div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu 
ul.ds-subsection-menu-ul
{

    visibility: hidden;
  transition-delay: 0s; /* react immediately on hover */
  position: absolute;
  left: auto;
  display: none;
  width: auto;
  margin-top: 0px;
  margin-left: 40px;
  min-width: 160px;
  top: auto;
  z-index: 9999;


}

div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu 
ul.ds-subsection-menu-ul:hover
{
	  transition: 0.2s 1s; 
   transition: 0.2s 1s; 
  visibility: visible;
  transition-delay: 0s; /* react immediately on hover */
  display: block !important;
  z-index: 9999;

}
div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li:hover button.ds-section-menu-option-top-level
{
      background-color: #6e2aa2;
    color: #1a1a1a;
width: 200px;
width: 100%;
  visibility: visible !important;
 display: block !important;
  
}
div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu 
ul.ds-subsection-menu-ul:hover  ~ ul.ds-options-section-list li.ds-section-menu-option-li  button.ds-section-menu-option-top-level div.ds-section-menu-option-text
{
   color: white;
width: 200px;
  visibility: visible !important;
 display: block !important;
  
}
div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li:hover
{
width: 200px;
}
div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li:hover button.ds-section-menu-option-top-level
div.ds-section-menu-option-text
{
	color: white;
	width: 200px;
	visibility: visible !important;
	display: block !important;
}



div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li:hover 
   ul.ds-subsection-menu-ul:first-of-type
{
   transition: 0.2s 1s; 
  visibility: visible;
  transition-delay: 0s; /* react immediately on hover */
  display: block !important;
  z-index: 9999;
}

div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li:hover 
   ul.ds-subsection-menu-ul li.ds-subection-menu-option-li
{
   margin: 0;
   padding: 0px;
}
/*
div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li 
 ul.ds-subsection-menu-ul:hover
{
    z-index: 999;
  display: block !important;
}
*/

div.ds-form-container {
	margin-left: 40px;
}


}

@media (min-width:961px) {
button[data-id=ds-options-menu-button] {
	display: none !important;
}
button[data-id=ds-options-close-menu-button] {
	display: none !important;
}
div.ds-options-menu-wrap.ds-menu-open.ds-sidebar-wp-open {
		left: 160px;

	}
div.ds-options-menu-wrap.ds-menu-open.ds-sidebar-wp-collapsed {
		left: 35px;

	}


	div.ds-form-container {
		margin-left: 5px;
	}
div.ds-options-menu-wrap.ds-menu-open div.ds-options-menu 
ul.ds-subsection-menu-ul
{
  display: block;

}

}

div.ds-options-menu-wrap.ds-menu-open div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button div.ds-section-menu-option-text {
	position: absolute;
	margin-top: -8px;
	margin-left: 35px;
	font-weight: 600;
}
	
	div.ds-options-menu-wrap.ds-menu-open {
	
		width: 200px;
	}

@media (max-width:781px) {

		div.ds-options-menu-wrap {
		left: 0px;
		top: 45px;
	}

}

@media (min-width:781px) {

	div.ds-options-menu-wrap {
		top: 30px; 
	}

}


.ds-form-label {

	margin-right: 10px;
}
.flex-center {
	align-items: center;
}
.flex-space {

	margin-right: 20px;
}
.flex-row {
	display: inline-flex;
	flex-wrap: nowrap;
}
.ds-options-w100 {
	width: 100%;
}
.ds-options-text-center {
	text-align: center;
}

.flex-col {
	display: inline-flex;
	flex-wrap: nowrap;
}
.ds-options-menu-form {
	/*margin-top: -35px;*/
}


table.ds-options-menu-form-table {

}

table.ds-options-menu-form-table td.ds-options-menu-form-table-title {
	width: 300px;
}

td.ds-options-section-form-table {

padding: 5px;
vertical-align: top;
margin: 0;
}
div.ds-options-menu-wrap {
	margin: 0;
	padding: 0px;
	z-index: 5000;
}
div.ds-options-menu-wrap div.ds-options-menu {
	vertical-align: top;
	padding: 0px;
	margin: 0;
	min-height: 1000px;
	height: 100%;
	border-top: 2px solid black;
	background-color: #2d2e39;
}
div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list {
	vertical-align: top;
	margin: 0;
	padding: 0px;
	
}
div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li{
	vertical-align: top;
	margin: 0;
}

div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button {
    background-color: inherit;
    color: white;
    border: 0;
	width: 100%;
	height: 30px;
	padding: 0px;
	margin: 0;
	padding-top: 20px;
	padding-bottom: 20px;
}

div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list li.ds-options-menu-button-container
button.ds-section-menu-button {
background-color: inherit;
    color: white;
    border: 0;
	width: 100%;
	height: 30px;
	margin: 0;
}
div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button:hover {
    background-color: #6e2aa2;
    color: #1a1a1a;
}

div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button div.ds-section-menu-option-icon:hover {

}

div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button div.ds-section-menu-option-icon {
	position: absolute;
	margin-top: -10px;
	margin-left: 5px;
}

div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-option-section-expanded.ds-section-menu-option-button {
	border-bottom: 2px solid #1a1a1a;

}

div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button.active {
	background-color: #8031bc;
	color: white;
}

div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button.active div.ds-section-menu-option-icon {
	color: white;
}

div.ds-options-menu-wrap div.ds-options-menu 
ul.ds-subsection-menu-ul li.ds-subection-menu-option-li
{
   font-size: 12px;
   font-weight: 300;

}

div.ds-options-menu-wrap div.ds-options-menu
ul.ds-subsection-menu-ul
{
   margin-top: -3px;
   background-color: #1a1a1a;

}

span.ds-options-form-error {

	color: red;

}


/*********************************************/
/*Form Icons*/
span.ds-form-icon-close-mini {
    padding: 5px;
    position: absolute;
    margin-top: -5px;
    margin-left: -5px;
    background-image: url('{$url}assets/images/formicons/close_mini.svg');
    background-repeat: no-repeat;
    background-size: 10px 10px;
}
span.ds-form-icon-arrow-down {
	background-image: url('{$url}assets/images/formicons/arrow_down.svg');
    padding: 15px;
    background-repeat: no-repeat;
    background-size: 15px 15px;
    position: absolute;
    margin-top: -7px;
    margin-left: -10px;

}
span.ds-form-icon-arrow-up {
  background-image: url('{$url}assets/images/formicons/arrow_up.svg');
  padding: 15px;
  background-repeat: no-repeat;
  background-size: 15px 15px;
  position: absolute;
  margin-top: -7px;
  margin-left: -10px;
}
/*********************************************/
/*Drop Down Search CSS*/
.ds-options-dropdownsearch-currentselected {
  color: black;
  width: 150px;
  font-size: 14px;
  border-radius: 5px;

  cursor: pointer;
}
.ds-options-dropdownsearch-currentselected span{
	padding: 0px;
  position: absolute;
  margin-top: -20px;
}
.ds-options-dropdownsearch-clearselect {
  width: 15px;
  height: 22px;
}
.ds-options-dropdownsearch-clearselect:hover {
 cursor: pointer;
}
.ds-options-dropdownsearch-clearselect span.ds-close-image {
    padding: 5px;
    position: absolute;
    margin-top: 5px;
    margin-left: -5px;
    background-image: url('{$url}assets/images/formicons/close_mini.svg');
    background-repeat: no-repeat;
    background-size: 10px 10px;
}


.ds-options-dropdownsearch-dropdownbutton {
    padding: 0px;
	border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
	height: 25px;
	width: 24px;
	margin-left: 0px;
	background-image: linear-gradient( #0071a1, #ccefff);
}
.ds-options-dropdownsearch-dropdownbutton span.ds-arrowdown-image.ds-image-rotate {
	background-image: url('{$url}assets/images/formicons/arrow_up.svg');
	border-top-right-radius: 0px;
  border-bottom-right-radius: 0px;
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
  position: absolute;
  margin-top: 5px;
  margin-left: 3px;
}
.ds-options-dropdownsearch-dropdownbutton:hover {
	background-color:  #016087;
	cursor: pointer;
}
.ds-options-dropdownsearch-dropdownbutton span.ds-arrowdown-image {
	background-image: url('{$url}assets/images/formicons/arrow_down.svg');
    padding: 15px;
    background-repeat: no-repeat;
    background-size: 15px 15px;
    position: absolute;
    margin-top: 5px;
    margin-left: 3px;

}
.ds-options-dropdownsearch-searchinput {
  background-image: url('{$url}assets/images/formicons/search.svg');
  background-position: 170px 5px;
  background-repeat: no-repeat;
  background-size: 20px 20px;
  font-size: 14px;
  padding: 14px 20px 12px 5px;
  border: none;
  width: 198px;
  height: 30px;
  border-bottom: 1px solid #ddd;
}

/*
.ds-options-dropdownsearch-searchinput:focus 
{outline: 3px solid #ddd;}
*/
.dropdown {
  width: 198px;
  height: 25px;
  border-radius: 5px;
  border: 1px solid grey;
  
  display: inline-flex;
  flex-wrap: nowrap;
}
.dropdown:hover, .dropdown:focus {
  background-color: #e6e6e6;

}
.dropdown-content {
  text-align: left !important;
  border-left: 1px solid grey;
  border-right: 1px solid grey;
  border-bottom: 1px solid grey;
  border-bottom-left-radius: 5px;
  border-bottom-right-radius: 5px;
  display: none;
  position: absolute;
  background-color: #f6f6f6;
  width: 200px;
  overflow: auto;
  z-index: 900;
  margin-top: -3px;

}

.dropdown-content a {
  color: black;
  padding: 5px 16px;
  text-decoration: none;
  display: block;
}
.dropdown-content.show {
	display: block;
}
.search-list-container {
	
	height: 150px;
  
	overflow-y: scroll;
}
.ds-dropdown-items div {
	display: inline-flex;
  flex-wrap: nowrap;
}
.ds-dropdown-items div span {
	display: inline-flex;
  flex-wrap: nowrap;
}

.ds-options-font-display {
	border: 1px solid grey;
	margin-top: 5px;
	margin-bottom: 5px;
	padding: 5px;
	width: 350px;
	height: 80px;
}
/*********************************************/
/*Drag And Drop Form Elements*/
.ds-form-sortablelist-button-container {
	float: right;
	clear: right;
	display: inline-block;
}
button.ds-form-remove-sortablelist-item-button {
	height: 20px;
	border: none;
	background-color: inherit;
}

button.ds-form-expand-sortablelist-item-button {
	height: 20px;
	border: none;
	width: 30px;
	background-color: inherit;
}
ul.ds-options-dnd-list {
	width: 200px;
}

ul.ds-options-dnd-list .ds-options-sortablelist-group-item {
  border-radius: 0;
  cursor: move;
  margin-bottom: 2px;
  padding: 5px;
  min-height: 20px;
}
.ds-options-sortablelist-ghost {
  border: 2px solid #0071a1;
  background-color: #e6e6e6;
}
.ds-options-dnd-list .list-group-item:hover {
  background-color: #f7f7f7;
}


.ds-options-list-top-content {
    border: 2px solid black;
    width: 100%;
    background-color: grey;
    color: white;
    min-height: 20px;
    padding: 5px;
}
.ds-options-list-bottom-content {
	border: 2px solid black;
    width: 100%;
    background-color: #b69f9f;
    min-height: 50px;
    padding: 5px;	

}
.ds-options-list-bottom-content input {
  width: 180px;
}
.ds-options-list-bottom-content input[type='checkbox'] {
  width: 15px;
}
.ds-options-list-bottom-content.ds-options-list-item-contracted {
	display: none;
}
.ds-options-list-bottom-content.ds-options-list-item-expanded {
	display: block;
}
/*********************************************/

</style>
HTML;	
	}










}