<?php 



class DivineStarOptionsForm
{
	
private $dso;


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
  	if($option['type'] == 'formhtml') {
  	return $option->value;
  	}
  	if($option['type'] == 'generic') { return '';}
 
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

<select id="{$name}-id" name="{$name}"  value="{$value}" {$dad}>
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
		<td><input name="{$name}" type="number" id="{$id}" aria-describedby="{$did}" value="{$value}" class="regular-text">
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
		<input name="{$name}" {$dad} type="checkbox" id="{$name}-id" value="1" {$checked}>$label</label>
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





	function get_options_form($options) {
		$form_html = '';
		$i = 0;
		$section_html = <<<HTML
		<table class="form-table ds-options-menu-table" role="presentation">
		<tbody>
		<tr>
		<td class='ds-options-menu-section-td'>
		<ul class='ds-options-section-list'>
HTML;
		
		//$sections = $this->load_options_xml('divinestarbookingoptions');
		$sections = $this->load_options_xml($options);
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

			<li id='{$name}' class='ds-section-menu-option-li '><button data-id='{$name}' class='ds-section-menu-option-button ds-section-menu-option-top-level {$buttoneclass} {$eclass}'><div class='ds-section-menu-option-icon dashicons-before dashicons-star-empty'></div><div class='ds-section-menu-option-text'>$title</div></button>$sshtml</li>
HTML;
			
			


		}
		$section_html .= <<<HTML
		</ul>
		</td>
HTML;	

        $css = $this->get_css();
        $js = $this->get_javascript();
		echo <<<HTML
		$section_html
		<td class='ds-options-section-form-table'>
		$form_html
		</td></tr>
		</tbody>
		</table>
		$css $js
HTML;
	}


private function get_javascript() {
   return <<<HTML
<script type="text/javascript">
	 jQuery(document).ready(function($) {

	 	$(".ds-options-menu-form").submit(function(event) {
	 		event.preventDefault();
	 		console.log("the form submited");
	 		var data = $(this).serializeArray(); 
	 		$(this).find('input[type="checkbox"]').each(function(){
	 		      if( !$(this).is(":checked")) {
	 		      	var name = $(this).attr('name');
	 		      	console.log(name);
	 		      	data.push({
	 		      		name:name,
	 		      		value: ''
	 		      	});
	 		      }
	 		});
	 		var data = JSON.stringify(data);
	 		var going_to = $(this).attr('data-going-to');
	 
	 		var send = {
	 			action: 'divine_star_updateoptions',
	 			going_to: going_to,
	 			data: data
	 		}
	 		$.ajax({ 
	 			type: 'post',
	 			url:ajaxurl,
	 			data:send,
	 			success: function(data){
	 				console.log(data);
	 			},error: function(data) {
	 				console.log('there was an error');
	 				console.log(data);

	 			}


	 		}); 




	 	});

       $(".ds-section-menu-option-button").click(function(event){
       		event.preventDefault();
       		var id = $(this).attr("data-id"); 
       		$(".ds-section-menu-option-button").each(function() {
       			$(this).removeClass("active");
       		});
       		$(this).addClass('active');

       	   if($(this).hasClass("ds-section-menu-option-top-level")) {	
       		if($(this).hasClass("ds-section-optoin-has-submenu")) {
    
       			$(".ds-section-menu-option-button.ds-section-menu-option-top-level").each(function(event){
       				$(this).removeClass('ds-option-section-expanded');
       			}); 
       			$(this).addClass('ds-option-section-expanded');
       		} else {
       			$(".ds-section-menu-option-button.ds-section-menu-option-top-level").each(function(event){
       				$(this).removeClass('ds-option-section-expanded');
       			}); 
       		}
       	   }

       		if($(this).hasClass("ds-section-menu-option-top-level")) {	
    
       		$("form.ds-options-menu-form").each(function(){
       			var fid = $(this).attr("id");
       			var forid = $(this).attr("data-for");
       			if(fid == id+'-form') {
       			$("#"+forid+" .ds-subsection-menu-ul").css("display","block");	
       			$(this).css("display","block");
       			} else {
       			$("#"+forid+" .ds-subsection-menu-ul").css("display","none");	
       			$(this).css("display","none");
       			}

       		});
       		} else {
       		$("form.ds-options-menu-form").each(function(){
       			var fid = $(this).attr("id");
       			var forid = $(this).attr("data-for");
       			if(fid == id+'-form') {
       			$(this).css("display","block");
       			} else {
       			$(this).css("display","none");
       			}

       		});


       	 }

       });

	 });

</script>


HTML;

}

private function get_css() {
	return <<<HTML
<style type="text/css">
	

.ds-options-menu-form {
	margin-top: -35px;
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
table.ds-options-menu-table {
	margin: 0;
	padding: 0px;
}
table.ds-options-menu-table td.ds-options-menu-section-td {
	vertical-align: top;
	padding: 0px;
	margin: 0;
	width: 200px;
	height: 100vh;
	border-top: 2px solid black;
	background-color: #2d2e39;
}
table.ds-options-menu-table td.ds-options-menu-section-td ul.ds-options-section-list {
	vertical-align: top;
	margin: 0;
	padding: 0px;
	
}
table.ds-options-menu-table td.ds-options-menu-section-td ul.ds-options-section-list li.ds-section-menu-option-li{
	vertical-align: top;
	margin: 0;
}

table.ds-options-menu-table td.ds-options-menu-section-td ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button {
    background-color: inherit;
    color: white;
    border: 0;
	width: 100%;
	height: 30px;
	padding: 0px;
}

table.ds-options-menu-table td.ds-options-menu-section-td ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button:hover {
    background-color: #6e2aa2;
    color: #1a1a1a;
}
table.ds-options-menu-table td.ds-options-menu-section-td ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button div.ds-section-menu-option-icon:hover {

}
table.ds-options-menu-table td.ds-options-menu-section-td ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button div.ds-section-menu-option-icon {
	position: absolute;
	margin-top: -10px;
	margin-left: 5px;
}
table.ds-options-menu-table td.ds-options-menu-section-td ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button div.ds-section-menu-option-text {
	position: absolute;
	margin-top: -8px;
	margin-left: 35px;
	font-weight: 600;
}

table.ds-options-menu-table td.ds-options-menu-section-td ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-option-section-expanded.ds-section-menu-option-button {
	border-bottom: 2px solid #1a1a1a;

}
table.ds-options-menu-table td.ds-options-menu-section-td ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button.active {
	background-color: #8031bc;
	color: white;
}
table.ds-options-menu-table td.ds-options-menu-section-td ul.ds-options-section-list li.ds-section-menu-option-li 
button.ds-section-menu-option-button.active div.ds-section-menu-option-icon {
	color: white;
}

table.ds-options-menu-table td.ds-options-menu-section-td  ul.ds-options-section-list li.ds-section-menu-option-li 
ul.ds-subsection-menu-ul li.ds-subection-menu-option-li
{
   font-size: 12px;
   font-weight: 300;

}
table.ds-options-menu-table td.ds-options-menu-section-td  ul.ds-options-section-list li.ds-section-menu-option-li 
ul.ds-subsection-menu-ul
{
   margin-top: -3px;
   background-color: #1a1a1a;

}
.ds-custom-page-title {
	width: 76vw;
	background-color: #1a1a1a;
    height: 50px;
    margin: 0;
    padding: 50px;
	text-align: center;
}
.ds-custom-page-title h2 {
	color: #8031bc;
	margin-top: 10px;
	font-size: 30px;
}
</style>
HTML;	
	}










}