<?php

namespace Nettools\Simple_Framework\Tests;



use \Nettools\Simple_Framework\Config\PrivateConfig;
use \Nettools\Simple_Framework\Config\ConfigObject;





class PrivateConfigTest extends \PHPUnit\Framework\TestCase
{
    public function testPrivateConfig()
    {
        // create underlying object
        $o = new ConfigObject((object)['secret1'=>'value1']);
		
		// this is not suitable !
		$this->assertContains('secret1', print_r($o, true));
		
		
		$private = new PrivateConfig($o);
		$this->assertContains('** HIDDEN **', print_r($private, true));
		$this->assertNotContains('secret1', print_r($private, true));
    }
   
}

?>