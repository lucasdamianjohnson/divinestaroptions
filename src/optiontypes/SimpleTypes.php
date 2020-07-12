<?php

class SimpleTypes extends Option
{

private $types;
private $simple_types;


	public function __construct() {
		$this->simple_type  = array("text","number","checkbox","selectdropdown");
	}


public function get_value_structure($type,$args,$mode = null) : array {

	return array();
}



 public function  get_data_strcutre($type,$args,$mode = null) : array {
    	return  array(
					'value' => $args,
					'type' => $type 
				); 
  }
	

  public function is_type($type) : bool {

  	     return array_search($type, $this->simple_type ) !== false;
  }




    public function get_html($type,$option,$value) : string {



  
        if($type == 'text') {
  	return $this->text_option($option,$value);
  		}

  	if($type == 'number') {


  	return $this->number_option($option,$value);



  		}

  	if($type == 'checkbox') {
  	return $this->check_box_option($option,$value);
  		}

  	if($type == 'selectdropdown') {
  	return $this->select_dropdown_option($option,$value);
  		}

  	if($type == 'generic') { return '';}

  	throw new Exception('No known simple type.');


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
		if(!is_numeric($value)) {
			//throw new Exception("The value provided is not numeric for the option $name.");
			//return '';
		}


		
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






}