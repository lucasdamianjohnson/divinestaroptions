<?php 
include('divinestaroptionscustomsfunctions.php');


class DivineStarOptionsForm
{
	
private $dso;
private $dsocf;

    function __construct() {
    	$this->dsocf = new DivineStarOptionsCustomFunctions;

        add_action( 'wp_ajax_divine_star_update_image_form', array( $this,'update_image_upload') );
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

   	 $data = $this->dso->get_json_data_structure('singleimage',
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


    function enqueue_scripts() {
      wp_enqueue_media();
      wp_enqueue_script('dsoption_form_functions',plugins_url('assets/js/functions.js',__FILE__) );
      wp_enqueue_script('dsoption_form_js',plugins_url('assets/js/form.js',__FILE__) );
     // wp_enqueue_script('dsoption_form_functions',OPTIONS_PATH.'assets/js/functions.js' );
     // wp_enqueue_script('dsoption_form_main',OPTIONS_PATH.'assets/js/form.js' );
    }


	function set_options($dso) {
		$this->dso = $dso;
	}

	private function load_options_xml($going_to) {

		return	simplexml_load_file(OPTIONS_PATH.'xml/'.$going_to.'.xml');
	}


  private function get_option_html($option) {


  	if($option['type'] == 'text') {
  	return $this->text_option($option,$this->dso->get_option((string)$option->name));
  		}

  	if($option['type'] == 'number') {
  	return $this->number_option($option,$this->dso->get_option((string)$option->name));
  		}

  	if($option['type'] == 'checkbox') {
  	return $this->check_box_option($option,$this->dso->get_option((string)$option->name));
  		}

  	if($option['type'] == 'selectdropdown') {
  	return $this->select_dropdown_option($option,$this->dso->get_option((string)$option->name));
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

  	if($option['type'] == 'generic') { return '';}
    
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

  	private function single_image_option($option,$value) {
  		
  		$name = $option->name;
		$title = $option->title;
		$size = (string) $option->size;
		$label = $option->label;
		$description = $option->description;
		$osize = $size;
		if(count(explode(" ", $size)) > 1) {
		$size = explode(" ", $size);
		} 

		$value = $value['id'];

		if($value !== "") {

		$post = implode(',',$this->wp_get_attachment($value));
		$imgdata = $this->wp_get_attachment($value);
		
		$src = wp_get_attachment_image_src( $value,  $size)[0];
      	$orgimage = wp_get_attachment_image_src( $value,  'full');
      	$orgimage_src =  $orgimage[0];
      	$orgimage_width =  $orgimage[1];
      	$orgimage_height =  $orgimage[2];

        } else {
        	$imgdata = array();
        	$orgimage_src = $orgimage_width = 
        	$orgimage_height = $imgdata['alt'] = $imgdata['title'] =
        	$imgdata['caption'] = $imgdata['description'] = 
        	$src = $osize =
        	 "";


        }

      	$id = "divinestaroptions[singleimage][$name]";
		return <<<HTML
		<tr>
		<th scope="row">
		<div class="">$label $value</div>
		</th>
		<td>
	
		<fieldset id="porto_settings-logo" class="" >
		
		<input placeholder="No media selected" type="text" class="upload large-text " name="{$id}[orgsrc]" id="{$id}[orgsrc]" value="{$orgimage_src}">
		<input data-for='dsoptions-singleimage-{$name}'  type="hidden"  name="{$id}[id]" id="{$id}[id]" value="{$value}">
		<input data-for='dsoptions-singleimage-{$name}'  type="hidden"  name="{$id}[mode]" id="{$id}[mode]" value="wp">
		<input data-for='dsoptions-singleimage-{$name}'  type="hidden" name="{$id}[src]" id="{$id}[src]" value="{$src}">
		<input data-for='dsoptions-singleimage-{$name}'  type="hidden" name="{$id}[size]" id="{$id}[size]" value="{$osize}">
		<input data-for='dsoptions-singleimage-{$name}'  type="hidden"  name="{$id}[orgheight]" id="{$id}[orgheight]" value="{$orgimage_height}">
		<input data-for='dsoptions-singleimage-{$name}'  type="hidden"  name="{$id}[orgwidth]" id="{$id}[orgwidth]" value="{$orgimage_width}">
		<input data-for='dsoptions-singleimage-{$name}'  type="hidden"  name="{$id}[title]" id="{$id}[title]" value="{$imgdata['title']}">
		<input data-for='dsoptions-singleimage-{$name}'  type="hidden"  name="{$id}[caption]" id="{$id}[caption]" value="{$imgdata['caption']}">
		<input data-for='dsoptions-singleimage-{$name}'  type="hidden"  name="{$id}[alt]" id="{$id}[alt]" value="{$imgdata['alt']}">
		<input data-for='dsoptions-singleimage-{$name}'  type="hidden"  name="{$id}[description]" id="{$id}[description]" value="{$imgdata['description']}">
		<div class="screenshot">
		<a class="" href="{$src}" target="_blank">
		<img class="" id="{$id}-image" src="{$src}" alt="" target="_blank" rel="external">
		</a>
		</div>
		<div class="">
		<span data-for='{$id}' class="button button-primary ds-options-upload-single-image-button" id="logo-media">Add</span>
		<span data-for='{$id}' class="button ds-options-remove-single-image-button" id="reset_logo" rel="logo">Remove</span>
		</div>
		</fieldset>
		</td>
		</tr>

HTML;

  	}


    private function custom_function_option($option) {

    	return call_user_func(array($this->dsocf, (string)$option->function),$option);
    }


  	private function select_dropdown_option($option,$value) {
		$name = $option->name;
		$title = $option->title;
		$label = $option->label;
		$description = $option->description;
        
        $did = '';$ds = '';$dad = '';
		if($description != '') {
		$did = $option->name . '-description';
		$ds = <<<HTML
		<p class="description" id="{$did}">$description</p>
HTML;
		$dad = "aria-describedby='$did'";
		}


		$o_html = '';
		foreach ($option->so->sog as $sog) {
			$glabel = $sog['label'];
			$o_html .= <<<HTML
			<optgroup label='{$glabel}'>
HTML;

			foreach($sog->o as $o ) {
			$ov = $o['value'];
			$ot = (string) $o;

			if($ov == $value) {
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}
			$o_html .= <<<HTML
			<option {$selected} value='{$ov}'>$ot</option>
HTML;
			}	

			$o_html .= <<<HTML
			<optgroup>
HTML;	
		}

		foreach ($option->so->o as $o) {
			$ov = $o['value'];
			$ot = (string) $o;

			if($ov == $value) {
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}
			$o_html .= <<<HTML
			<option {$selected} value='{$ov}'>$ot</option>
HTML;
		}

		return <<<HTML
<tr>
<th scope="row"><label for="{$name}-id">$label</label></th>
<td>

<select id="{$name}-id" name="divinestaroptions[selectdropdown][{$name}]"  value="{$value}" {$dad}>
$o_html 
</select>
$ds
</td>

</tr>
HTML;




  	}

	private function number_option($option,$value) {
		$name = $option->name;
		$label = $option->label;
		$description = $option->description;
		$id = $option->value . '-id';
		$did = $option->name . '-description';
		return <<<HTML

		<tr>
		<th scope="row"><label for="{$id}">$label</label></th>
		<td><input name="divinestaroptions[number][{$name}]" type="number" id="{$id}" aria-describedby="{$did}" value="{$value}" class="regular-text">
		<p class="description" id="{$did}">$description</p></td>
		</tr>
HTML;
	}







	private function check_box_option($option,$value) {
		$name = $option->name;
		$title = $option->title;
		$label = $option->label;
		$description = $option->description;
        
        $did = '';$ds = '';$dad = '';
		if($description != '') {
		$did = $option->name . '-description';
		$ds = <<<HTML
		<p class="description" id="{$did}">$description</p>
HTML;
		$dad = "aria-describedby='$did'";
		}
        $checked = '';
		if($value != ''){
		$checked = 'checked=""';
		}

		$id = $option->value . '-id';
		$did = $option->name . '-description';
		return <<<HTML
		<tr>
		<th scope="row">$title</th>
		<td> <fieldset><legend class="screen-reader-text"><span>$title</span></legend><label for="{$name}-id">
		<input name="divinestaroptions[checkbox][{$name}]" {$dad} type="checkbox" id="{$name}-id" value="1" {$checked}>$label</label>
		</fieldset>
		$ds
		</td>
		</tr>
HTML;
	}

	private function text_option($option,$value) {
		$name = $option->name;
		$label = $option->label;
		$description = $option->description;
		$id = $option->value . '-id';
		$did = $option->name . '-description';
		return <<<HTML

		<tr>
		<th scope="row"><label for="{$id}">$label</label></th>
		<td><input name="divinestaroptions[text][{$name}]" type="text" id="{$id}" aria-describedby="{$did}" value="{$value}" class="regular-text">
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
		</div>
		</div>
		<div class='flex-row'>
		<div class='flex-col'>
		<div class='ds-form-container'>
		$form_html
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
	z-index: 999;
}

div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu 
ul.ds-subsection-menu-ul
{
  transition: 0.2s 1s; 
  visibility: hidden;
  display: none !important;
}
div.ds-options-menu-wrap.ds-menu-folded div.ds-options-menu 
ul.ds-subsection-menu-ul:hover
{
    visibility: visible;
  transition-delay: 0s; /* react immediately on hover */
  position: absolute;
  left: auto;
  display: block;
  width: 100%;
  margin-left: 40px;
  min-width: 175px;
  top: auto;
  z-index: 999;

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
  visibility: visible;
  transition-delay: 0s; /* react immediately on hover */
  position: absolute;
  left: auto;
  width: 100%;
  margin-left: 40px;
  min-width: 175px;
  top: auto;
  z-index: 999;
  display: block !important;
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
		margin-left: 200px;
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




.flex-row {
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
	position: fixed;

}
div.ds-options-menu-wrap div.ds-options-menu {
	vertical-align: top;
	padding: 0px;
	margin: 0;

	height: 100vh;
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
}

div.ds-options-menu-wrap div.ds-options-menu ul.ds-options-section-list li.ds-options-menu-button-container
button.ds-section-menu-button {
background-color: inherit;
    color: white;
    border: 0;
	width: 100%;
	height: 30px;
	padding: 0px;
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


</style>
HTML;	
	}










}