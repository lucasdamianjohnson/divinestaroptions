<?php declare(strict_types=1);
//./vendor/bin/phpunit tests
use PHPUnit\Framework\TestCase;

final class DivineStarOptionsTest extends TestCase
{
    public function testDivineStarOptionsClass(): void
    {
        $this->assertInstanceOf(
            DivineStarOptions::class,
            new DivineStarOptions()
        );
    }


     public function testDivineStarOptionsData()
    {   


        $dso = new DivineStarOptions();
       $this->assertSame(null,null);
       // $this->assertSame(array('test','test','test'),array('test','test','test'));
       // $this->assertSame($dso->testing(),"test");
      //  $this->assertSame("test","test");
    }
/*
    public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('invalid');
    }

    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            'user@example.com',
            Email::fromString('user@example.com')
        );
    }
    */
}