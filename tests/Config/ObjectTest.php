<?php

use \Nettools\Simple_Framework\Config\Object;





class ObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testObjectNoParameter()
    {
        // no parameter to constructor
        $o = new Object();
    }
    
    
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testObjectArray()
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

    
}

?>