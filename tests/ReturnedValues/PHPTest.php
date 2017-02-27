<?php

use \Nettools\Simple_Framework\ReturnedValues\PHP;





class PHPTest extends PHPUnit\Framework\TestCase
{
    public function testValue()
    {
        $v = new PHP('string');
        $this->assertEquals(true, $v->isSuccessful());
        $this->assertEquals('string', (string)$v);
    }
    

    public function testNotSuccessful()
    {
        $v = new PHP(12, false);
        $this->assertEquals(false, $v->isSuccessful());
        $this->assertEquals('12', (string)$v);
    }
    
    
    public function testNoOutput()
    {
        $this->expectOutputString('');
        $v = new PHP('string');
        $v->output();       // a PHP value does not output to stdout
    }
    
    
 
}

?>