<?php declare(strict_types=1);
//./vendor/bin/phpunit tests
use PHPUnit\Framework\TestCase;

final class OptionsTest extends TestCase
{





    
        public function testOptions() : void
    {   

        $form = new DivineStarOptionsForm();
        $options = $form->get_options();
  
        $types = array(
          'SimpleTypes',
          'Content',
          'Images',
          'Generic'
        );
        foreach ($types as $key => $type) {
        $this->assertIsObject($options->get_option($type));
        }


       

    }
     /**
     * @depends testOptions
     */
   
    public function testOptionsValueStructure() : void
  {

      //$long = str_repeat(md5((string)time()), 1);

      $form = new DivineStarOptionsForm();
      $options = $form->get_options();

      $imgvalue =array( 
        'id' =>  '',
        'orgsrc' => '',
        'size' => '',
        'src' => '',
        'alt' => '',
        'title'=> '',
        'caption'=> '',
        'description'=>'',
        'orgwidth' =>'',
        'orgheight' => ''
      );
      $testvalue =  $options->get_value_structure('singleimage',[ '','','','','','','','','',''],"wp");
      $this->assertSame($imgvalue,$testvalue);
       

      $imgvalue =array( 
        'url' => '',
        'alt' => '',
        'title'=> '',
        'caption'=> '',
        'description'=> '',
        'width' => '',
        'height' => ''
      );
      $testvalue =  $options->get_value_structure('singleimage',[ '','','','','','','',''],"url");
      $this->assertSame($imgvalue,$testvalue);

  }



  public function testOptionsHTMLOutput() : void
  {
    $dso = new DivineStarOptions;
    $form = new DivineStarOptionsForm();
    $options = $form->get_options();
    $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<dsoptiondebug>
  <section for='generaloptions' name="mainoptins" title="Main Options" icon="dashicons-dashboard">
    <option type="text">
      <label>Text Option 1</label>
      <name>text1</name>
      <value>text</value>
      <description>This is the first test option.</description>
    </option>
  </section>
</dsoptiondebug>
XML;
  $expecthtml = <<<HTML
<tr>
<th scope="row">
<label for="text1-id">Text Option 1</label>
</th>
<td>
<input name="divinestaroptions[text][text1]" type="text" id="text1-id" aria-describedby="text1-description" value="text" class="regular-text">
<p class="description" id="text1-description">This is the first test option.</p>
</td>
</tr>
HTML;
  
    $sections = $dso->load_options_xml_string($xml);
    $i = 0;

    $text = '';
    foreach($sections->section as $section) {

      foreach($section->option as $option){
        $type = (string) $option['type'];
         $text = $options->get_html($type,$option,(string)$option->value);
      }

    }

    $trim1 = str_replace(' ', '', $expecthtml);
    $trim2 = str_replace(' ', '',$text );
    $this->assertSame(trim($trim1),trim($trim2));


  }

}