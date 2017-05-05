<?php

namespace Nettools\Simple_Framework\Tests;



use \Nettools\Simple_Framework\Config\Object;





class ObjectTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException TypeError
     */
    public function testNoParameter()
    {
        // no parameter to constructor
        $o = new Object();
    }
    
    
    /**
     * @expectedException TypeError
     */
    public function testArray()
    {
        // parameter is not a Stdclass object
        $o = new Object(array('property'=>'value'));
    }
    
    
    public function testObject()
    {
        $o = new Object((object) array('property'=>'value'));
        $this->assertEquals('value', $o->property);
        $this->assertEquals(NULL, $o->no_property);
    }
    
    
    /**
     * @expectedException \Nettools\Simple_Framework\Exceptions\NotAuthorizedException
     */
    public function testObjectReadonly()
    {
        $o = new Object((object) array('property'=>'value'));
        $this->assertEquals(true, $o->isReadOnly());
        $o->property = 'value2';    // always readonly
    }


    public function testObjectReadWrite()
    {
        $o = new Object((object) array('property'=>'value'), false);
        $this->assertEquals(false, $o->isReadOnly());
        $o->property = 'value2'; // no exception here
        $this->assertEquals('value2', $o->property);
    }


    public function testAsjson()
    {
        $o = new Object((object) array('property'=>'value', 'property2'=>12, 'property3'=>NULL, 'property4'=>[], 'property5'=>(object)array()));
        $this->assertEquals(json_encode(json_decode('{"property":"value","property2":12,"property3":null,"property4":[],"property5":{}}'), JSON_PRETTY_PRINT), $o->asJson());
    }
    
    
   
}

?>