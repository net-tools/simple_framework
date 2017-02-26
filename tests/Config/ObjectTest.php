<?php

use \Nettools\Simple_Framework\Config\Object;





class ObjectTest extends PHPUnit\Framework\TestCase
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
        // parameter is not a Stdclass object
        $o = new Object((object) array('property'=>'value'));
        $this->assertEquals('value', $o->property);
        $this->assertEquals(NULL, $o->no_property);
        
        $o->property = 'value2';
        $this->assertEquals('value2', $o->property);
                
        $this->assertEquals('{"property":"value2"}', $o->asJson());
        return $o;
    }
    
    
    /**
     * @expectedException \Nettools\Simple_Framework\Exceptions\NotAuthorizedException
     */
    public function testObjectReadonly()
    {
        // parameter is not a Stdclass object
        $o = new Object((object) array('property'=>'value'));
        $o->property = 'value2';    // always readonly
    }


    /**
     * @depends testObject
     * @expectedException \Nettools\Simple_Framework\Exceptions\NotAuthorizedException
     */
    public function testCommit(Object $o)
    {
        $o->doCommit();        
    }
    
    
   
}

?>