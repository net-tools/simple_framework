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


    public function testCommit()
    {
        $o = new Object((object) array('property'=>'value'));
        $o->commit();        // nothing done in Config::commit since Object is readonly
    }
    
    
   
}

?>