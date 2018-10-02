<?php
require_once __DIR__ . '/../vendor/autoload.php';

class validationTest extends \PHPUnit\Framework\TestCase
{

    public function testValidPhones()
    {

        // cz
        $this->assertTrue( \ProfiCloS\Tools\PhoneNumber::isPhone('777666555'), '777666555' );
        $this->assertTrue( \ProfiCloS\Tools\PhoneNumber::isPhone('777001000'), '777001000' );
        $this->assertTrue( \ProfiCloS\Tools\PhoneNumber::isPhone('00420777001000'), '00420777001000' );
        $this->assertTrue( \ProfiCloS\Tools\PhoneNumber::isPhone('+420777001000'), '+420777001000' );

        // sk
        $this->assertTrue( \ProfiCloS\Tools\PhoneNumber::isPhone('+421 777 001 000'), '+421 777 001 000' );
        $this->assertTrue( \ProfiCloS\Tools\PhoneNumber::isPhone('00421 777 001 000'), '00421 777 001 000' );

        // pl
        $this->assertTrue( \ProfiCloS\Tools\PhoneNumber::isPhone('+48777001000'), '+48777001000' );
        $this->assertTrue( \ProfiCloS\Tools\PhoneNumber::isPhone('0048777001000'), '0048777001000' );

        // hu
        $this->assertTrue( \ProfiCloS\Tools\PhoneNumber::isPhone('+3677700100'), '+3677700100' );
        $this->assertTrue( \ProfiCloS\Tools\PhoneNumber::isPhone('003677700100'), '003677700100' );
    }

    public function testInValidPhones()
    {

        $this->assertFalse( \ProfiCloS\Tools\PhoneNumber::isPhone('77766655') );
        $this->assertFalse( \ProfiCloS\Tools\PhoneNumber::isPhone('9') );
        $this->assertFalse( \ProfiCloS\Tools\PhoneNumber::isPhone('123') );
        $this->assertFalse( \ProfiCloS\Tools\PhoneNumber::isPhone('958 656') );

		$this->assertFalse( \ProfiCloS\Tools\PhoneNumber::isPhone('7776g66555'), '7776g66555' );
		$this->assertFalse( \ProfiCloS\Tools\PhoneNumber::isPhone('7s77001000'), '7s77001000' );
		$this->assertFalse( \ProfiCloS\Tools\PhoneNumber::isPhone('00420777001000h'), '00420777001000h' );
		$this->assertFalse( \ProfiCloS\Tools\PhoneNumber::isPhone('a+420777001000'), 'a+420777001000' );

    }

    public function testInvalidChars()
    {

        $this->assertTrue( \ProfiCloS\Tools\PhoneNumber::isPhone('608 500 000') );

    }

}
