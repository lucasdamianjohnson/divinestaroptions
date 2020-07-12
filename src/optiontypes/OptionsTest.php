<?php declare(strict_types=1);
require_once('Options.php');
//./vendor/bin/phpunit tests
use PHPUnit\Framework\TestCase;

final class OptionsTest extends TestCase
{



    public function testOptionTypeInstances() {
       $this->assertInstanceOf(
            Options::class,
            new Options()
        );
        $this->assertInstanceOf(
            SimpleTypes::class,
            new SimpleTypes()
        );
        $this->assertInstanceOf(
            Content::class,
            new Content()
        );
       $this->assertInstanceOf(
            Generic::class,
            new Generic()
        );
      $this->assertInstanceOf(
            Images::class,
            new Images()
        );

    }


        /**
        * @depends testOptionTypeInstances
        */
        public function testOptions()
    {   


        $options = new Options();
        //$this->assertSame($options->get_option('text'),null);
      // $this->assertInstanceOf($options->get_option('SimpleTypes')::class,new SimpleTypes);
       $this->assertIsObject($options->get_option('SimpleTypes'));
       // $this->assertSame(array('test','test','test'),array('test','test','test'));
       // $this->assertSame($dso->testing(),"test");
      //  $this->assertSame("test","test");
    }

    /**
    * @depends testOptionTypeInstances
    */
    public function testOptionsValueStructure()
  {

      //$long = str_repeat(md5((string)time()), 1);

      $options = new Options();

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













}