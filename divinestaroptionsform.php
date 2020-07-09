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
		<div id='ds-options-menu-js-id' class="ds-options-menu-wrap ds-menu-open">
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



//https://github.com/jfriend00/docReady
(function(funcName, baseObj) {
    "use strict";
    // The public function name defaults to window.docReady
    // but you can modify the last line of this function to pass in a different object or method name
    // if you want to put them in a different namespace and those will be used instead of 
    // window.docReady(...)
    funcName = funcName || "docReady";
    baseObj = baseObj || window;
    var readyList = [];
    var readyFired = false;
    var readyEventHandlersInstalled = false;
    
    // call this when the document is ready
    // this function protects itself against being called more than once
    function ready() {
        if (!readyFired) {
            // this must be set to true before we start calling callbacks
            readyFired = true;
            for (var i = 0; i < readyList.length; i++) {
                // if a callback here happens to add new ready handlers,
                // the docReady() function will see that it already fired
                // and will schedule the callback to run right after
                // this event loop finishes so all handlers will still execute
                // in order and no new ones will be added to the readyList
                // while we are processing the list
                readyList[i].fn.call(window, readyList[i].ctx);
            }
            // allow any closures held by these functions to free
            readyList = [];
        }
    }
    
    function readyStateChange() {
        if ( document.readyState === "complete" ) {
            ready();
        }
    }
    
    // This is the one public interface
    // docReady(fn, context);
    // the context argument is optional - if present, it will be passed
    // as an argument to the callback
    baseObj[funcName] = function(callback, context) {
        if (typeof callback !== "function") {
            throw new TypeError("callback for docReady(fn) must be a function");
        }
        // if ready has already fired, then just schedule the callback
        // to fire asynchronously, but right away
        if (readyFired) {
            setTimeout(function() {callback(context);}, 1);
            return;
        } else {
            // add the function and context to the list
            readyList.push({fn: callback, ctx: context});
        }
        // if document already ready to go, schedule the ready function to run
        // IE only safe when readyState is "complete", others safe when readyState is "interactive"
        if (document.readyState === "complete" || (!document.attachEvent && document.readyState === "interactive")) {
            setTimeout(ready, 1);
        } else if (!readyEventHandlersInstalled) {
            // otherwise if we don't have event handlers installed, install them
            if (document.addEventListener) {
                // first choice is DOMContentLoaded event
                document.addEventListener("DOMContentLoaded", ready, false);
                // backup is window load event
                window.addEventListener("load", ready, false);
            } else {
                // must be IE
                document.attachEvent("onreadystatechange", readyStateChange);
                window.attachEvent("onload", ready);
            }
            readyEventHandlersInstalled = true;
        }
    }
})("docReady", window);

	

docReady(function() {


	var menu_button = document.querySelector("button[data-id=ds-options-menu-button]");
	var close_menu_button = document.querySelector("button[data-id=ds-options-close-menu-button]");
	var menu_bar = document.getElementById('ds-options-menu-js-id');
	menu_button.addEventListener("click", function() {
			   addClass(menu_bar,' ds-menu-open ');
  			   removeClass(menu_bar,'ds-menu-folded');
  			   menu_button.style.display = "none";
  			   close_menu_button.style.display = "block";
	});
	
	close_menu_button.addEventListener("click", function() {
			   removeClass(menu_bar,'ds-menu-open');
			   addClass(menu_bar,' ds-menu-folded ');
  			   
  			   close_menu_button.style.display = "none";
  			   menu_button.style.display = "block";
	});


		function check_menu() {

			var width = window.innerWidth;
	       if(width > 961) {
	       	   if(!hasClass(menu_bar,'ds-menu-open')) {
  			   addClass(menu_bar,' ds-menu-open ');
  			   removeClass(menu_bar,'ds-menu-folded');
  			   menu_button.style.display = "none";
  			   close_menu_button.style.display = "none";
  			   }
	       } else if(width < 961) {
	       	   if(!hasClass(menu_bar,'ds-menu-folded')) {
	       	   addClass(menu_bar,' ds-menu-folded ');
	       	   removeClass(menu_bar,'ds-menu-open');
	       	   menu_button.style.display = "block";
  			   close_menu_button.style.display = "none";
	       	   }
	       }

		}

		window.addEventListener("resize", function() {
		check_menu();
	});

		check_menu();





	console.log('doc is ready!');
});


function hasClass(element, class_name) {
    return (' ' + element.className + ' ').indexOf(' ' + class_name+ ' ') > -1;
}

function removeClass(element,remove_class) {

  var re = new RegExp(remove_class,"g");
  element.className = element.className.replace(re, "");

}

function addClass(element,add_class) {

  classes = element.className.split(" ");
  if (classes.indexOf(add_class) == -1) {
    element.className += " " + add_class;
  }

}

function toggleClass(element,toggle_class) { 

if (element.classList) {
  element.classList.toggle(toggle_class);
} else {
  // For IE9
  var classes = element.className.split(" ");
  var i = classes.indexOf(toggle_class);

  if (i >= 0)
    classes.splice(i, 1);
  else
    classes.push(toggle_class);
    element.className = classes.join(" ");
}

}





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
       			console.log(id);
       		if(id == 'ds-options-menu-button' || id == 'ds-options-close-menu-button' ) {
                console.log('pressed menu button!');
       			return;
       		}

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


		div.ds-options-menu-wrap {
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
   width: 200px;
  
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
div.ds-options-menu-wrap.ds-menu-open {
		left: 160px;

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


.ds-custom-page-title {
	width: 100%;
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