<?php

namespace Nettools\Simple_Framework\Tests;



use \Nettools\Simple_Framework\Config\ConfigObject;





class ObjectTest extends \PHPUnit\Framework\TestCase
{
    public function testNoParameter()
    {
     	$this->expectException(\TypeError::class);
		
		
        // no parameter to constructor
        new ConfigObject();
    }
    
    
    public function testArray()
    {
     	$this->expectException(\TypeError::class);

		
		// parameter is not a Stdclass object
        new ConfigObject(array('property'=>'value'));
    }
    
    
    public function testObject()
    {
        $o = new ConfigObject((object) array('property'=>'value'));
        $this->assertEquals('value', $o->property);
        $this->assertEquals(NULL, $o->no_property);
    }
    
    
    public function testObjectReadonly()
    {
		$this->expectException(\Nettools\Simple_Framework\Exceptions\NotAuthorizedException::class);
  
		
		$o = new ConfigObject((object) array('property'=>'value'));
        $this->assertEquals(true, $o->isReadOnly());
        $o->property = 'value2';    // always readonly
    }


    public function testObjectReadWrite()
    {
        $o = new ConfigObject((object) array('property'=>'value'), false);
        $this->assertEquals(false, $o->isReadOnly());
        $o->property = 'value2'; // no exception here
        $this->assertEquals('value2', $o->property);
    }


    public function testAsjson()
    {
        $o = new ConfigObject((object) array('property'=>'value', 'property2'=>12, 'property3'=>NULL, 'property4'=>[], 'property5'=>(object)array()));
        $this->assertEquals(json_encode(json_decode('{"property":"value","property2":12,"property3":null,"property4":[],"property5":{}}'), JSON_PRETTY_PRINT), $o->asJson());
    }
    
    
   
}

?>