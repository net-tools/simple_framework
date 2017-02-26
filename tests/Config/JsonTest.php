<?php

use \Nettools\Simple_Framework\Config\Json;




class JsonTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Nettools\Simple_Framework\Exceptions\NotAuthorizedException
     */
    public function testNoFile()
    {
        // file doesn't exist : empty config ; config is read-only
        $o = new Json('/nofile', true);
        $this->assertEquals('{}', $o->asJson());
        
        // not allowed since Config object is read-only
        $o->property = value;
    }
    
   
}

?>