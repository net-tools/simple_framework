<?php

use \Nettools\Simple_Framework\Config\Json;





class JsonTest extends PHPUnit\Framework\TestCase
{
    public function testNoParameter()
    {
        $o = new Json();
        $this->assertEquals('{}', $o->asJson());
    }
    
    
    public function testConstructor()
    {
        $o = new Json('{"prop":12}');
        $this->assertEquals(12, $o->prop);
        $this->assertEquals(true, $o->isReadOnly());
    }
    
    
    /**
     * @expectedException \Nettools\Simple_Framework\Exceptions\NotAuthorizedException
     */
    public function testReadonly()
    {
        $o = new Json('{"prop":12}');
        $o->prop = 0;   // exception here, Json config is constructed readonly
    }
    
    
    public function testReadWrite()
    {
        $o = new Json('{"prop":12}', false);
        $o->prop = 0;
        $this->assertEquals(0, $o->prop);
    }
    
}

?>