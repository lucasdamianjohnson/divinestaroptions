<?php declare(strict_types=1);
//./vendor/bin/phpunit tests
use PHPUnit\Framework\TestCase;

final class OptionsTest extends TestCase
{





    
        public function testOptions()
    {   

        $form = new DivineStarOptionsForm();
        $options = $form->get_options();
        //$this->assertSame($options->get_option('text'),null);
      // $this->assertInstanceOf($options->get_option('SimpleTypes')::class,new SimpleTypes);
       $this->assertIsObject($options->get_option('SimpleTypes'));
       // $this->assertSame(array('test','test','test'),array('test','test','test'));
       // $this->assertSame($dso->testing(),"test");
      //  $this->assertSame("test","test");
    }

   
    public function testOptionsValueStructure()
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
       $this->assertSame($imgvalue,'');

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













}