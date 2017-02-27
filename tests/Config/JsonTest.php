<?php

use \Nettools\Simple_Framework\Config\Object;





class JsonTest extends PHPUnit\Framework\TestCase
{
    public function testNoParameter()
    {
        $o = new Object();
        $this->assertEquals('{}', $o->asJson());
    }
    
    
    public function testConstructor()
    {
        $o = new Object('{"prop":12}');
        $this->assertEquals(12, $o->prop);
        $this->assertEquals(true, $o->isReadOnly());
    }
    
    
    /**
     * @expectedException \Nettools\Simple_Framework\Exceptions\NotAuthorizedException
     */
    public function testReadonly()
    {
        $o = new Object('{"prop":12}');
        $o->prop = 0;   // exception here, Json config is constructed readonly
    }
    
    
    public function testReadWrite()
    {
        $o = new Object('{"prop":12}', false);
        $o->prop = 0;
        $this->assertEquals(0, $o->prop);
    }
    
}

?>