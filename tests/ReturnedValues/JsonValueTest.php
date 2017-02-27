<?php

use \Nettools\Simple_Framework\ReturnedValues\Json;





class JsonValueTest extends PHPUnit\Framework\TestCase
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
    
    
    /**
     * @expectedException \Nettools\Simple_Framework\Exceptions\InvalidParameterException
     */
    public function testNoJson()
    {
        $v = new Json('abc'); // not json-formatted
    }
    
    
/*    public function testNoOutput()
    {
        $this->expectOutputString('{"prop":"value"}');
        $v = new Json('{"prop":"value"}');
        $v->output();      
    }
*/    
    
 
}

?>