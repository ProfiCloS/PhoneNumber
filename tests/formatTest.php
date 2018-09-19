<?php
require_once __DIR__ . '/../vendor/autoload.php';

class formatTest extends \PHPUnit\Framework\TestCase
{

    public function testBasicFormats()
    {

        $phone = \ProfiCloS\Tools\PhoneNumber::from('777666555');

        $phone->setFormat( \ProfiCloS\Tools\PhoneNumber::FORMAT_INTERNATIONAL );
        $this->assertSame('00420777666555' , (string) $phone );

        $phone->setFormat( \ProfiCloS\Tools\PhoneNumber::FORMAT_GSM );
        $this->assertSame('+420777666555' , (string) $phone );

        $phone->setFormat( \ProfiCloS\Tools\PhoneNumber::FORMAT_CANONICAL );
        $this->assertSame('+420 777 666 555' , (string) $phone );

        $phone->setFormat( \ProfiCloS\Tools\PhoneNumber::FORMAT_FULL );
        $this->assertSame('+420 777 666 555' , (string) $phone );

    }

}
