<?php

namespace Nettools\Simple_Framework\Tests;



use \Nettools\Simple_Framework\Config\Json;





class JsonTest extends \PHPUnit\Framework\TestCase
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
    
    
    public function testReadonly()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\NotAuthorizedException::class);
		
		
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