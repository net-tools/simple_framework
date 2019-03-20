<?php

namespace Nettools\Simple_Framework\Tests;



use \Nettools\Simple_Framework\ReturnedValues\Json;





class JsonValueTest extends \PHPUnit\Framework\TestCase
{
    public function testValue()
    {
        $v = new Json('{"prop":"value"}');
        $this->assertEquals(true, $v->isSuccessful());
        $this->assertEquals('{"prop":"value"}', (string)$v);
    }
    

    public function testNotSuccessful()
    {
        $v = new Json('{}', false);
        $this->assertEquals(false, $v->isSuccessful());
        $this->assertEquals('{}', (string)$v);
    }
    
    
    public function testNoJson()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidParameterException::class);
        $v = new Json('abc'); // not json-formatted
    }
    
    
    public function testOutput()
    {
        $v = new Json('{"prop":"value"}');
        $this->assertEquals('{"prop":"value"}', $v);
    }
    
 
}

?>