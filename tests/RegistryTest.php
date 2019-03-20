<?php

namespace Nettools\Simple_Framework\Tests;



use \Nettools\Simple_Framework\Registry;
use \Nettools\Simple_Framework\Config\ConfigObject;





class RegistryTest extends \PHPUnit\Framework\TestCase
{
    public function testEmptyRegistry()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidParameterException::class);

		
		$r = new Registry(array());
        $this->assertEquals(false, $r->exists('reg'));
        $r->reg->prop = 12;     // registry named 'reg' doesn't exist
    }
    
 
    public function testWrongTypeRegistry()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidParameterException::class);

		
		$r = new Registry(array('reg'=>array()));       // wrong type here, must implement Config\Config
    }
    
 
    public function testRegistry()
    {
        $r = new Registry(array('reg'=>new ConfigObject((object)array('prop'=>10))));
        $this->assertEquals(true, $r->exists('reg'));
        $this->assertEquals(10, $r->reg->prop);    
    }
    
 
}

?>