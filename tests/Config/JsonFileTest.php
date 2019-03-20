<?php

namespace Nettools\Simple_Framework\Tests;



use \Nettools\Simple_Framework\Config\JsonFile;




class JsonFileTest extends \PHPUnit\Framework\TestCase
{
    public function setUp() :void
    {
        $f = fopen('/tmp/net-tools-phpunit-' . basename(__FILE__), 'w');
        fwrite($f, '{"prop1":"value1","prop2":false,"prop3":13}');
        fclose($f);
    }
    
    
    public function tearDown() :void
    {
        $f = '/tmp/net-tools-phpunit-' . basename(__FILE__);
        if ( file_exists($f) )
            unlink($f);
    }
    

    public function testNoFile()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\NotAuthorizedException::class);

		
		// file doesn't exist : empty config ; config is read-only
        $o = new JsonFile('/nofile', true);
        $this->assertEquals('{}', $o->asJson());
        
        // not allowed since Config object is read-only
        $o->property = 'value';
    }
    

    public function testFileReadonlyByDefault()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\NotAuthorizedException::class);

		
		// by default, the Config object is readonly
        $f = '/tmp/net-tools-phpunit-' . basename(__FILE__);
        $o = new JsonFile($f);
        $o->prop4 = '4';    // exception here, setting a property is not allowed on readonly
    }
    

    public function testFile()
    {
        // by default, the Config object is readonly
        $f = '/tmp/net-tools-phpunit-' . basename(__FILE__);
        $o = new JsonFile($f, false);
        $o->prop4 = 'p4';
        $o->commit();
        $this->assertEquals(json_encode(json_decode('{"prop1":"value1","prop2":false,"prop3":13,"prop4":"p4"}'), JSON_PRETTY_PRINT), file_get_contents($f));
    }
    
    
   
}

?>