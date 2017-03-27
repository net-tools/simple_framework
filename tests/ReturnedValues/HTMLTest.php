<?php

namespace Nettools\Simple_Framework\Tests;



use \Nettools\Simple_Framework\ReturnedValues\HTML;





class HTMLTest extends \PHPUnit\Framework\TestCase
{
    public function testValue()
    {
        $v = new HTML('<b>string</b>');
        $this->assertEquals(true, $v->isSuccessful());
        $this->assertEquals('<b>string</b>', (string)$v);
    }
    

    public function testNotSuccessful()
    {
        $v = new HTML('<div>fun</div>', false);
        $this->assertEquals(false, $v->isSuccessful());
        $this->assertEquals('<div>fun</div>', (string)$v);
    }
    
    
    public function testNoOutput()
    {
        $this->expectOutputString('');
        $v = new HTML('<i>string</i>');
        $v->immediateOutput();       // a PHP value does not output to stdout
        $this->assertEquals('<i>string</i>', $v->getValue());
    }
    
    
 
}

?>