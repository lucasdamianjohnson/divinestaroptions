<?php


class Images extends Option  
{





	 public function get_value_structure($type,$args,$mode = null) : array {
		$imgvalue = array();
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

    	return $imgvalue;

	}

	public function get_data_strcutre($type,$args,$mode = null) : array {


    			if($type == 'singleimage') {

    			$imgvalue =  $this->get_value_structure($type,$args,$mode=null);

    		
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


			return array(false);

	}

	public function get_html($type,$option,$value) : string {

		if($type == 'singleimage') {
			return$this->single_image_option($option,$value);
		}

		return '';
	}

	public function is_type($type) : bool {
		$types = array(
			'singleimage'
		);
		return array_search($type, $types ) !== false;
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


}