<?php

namespace Nettools\Simple_Framework\Tests;



use \Nettools\Simple_Framework\ReturnedValues\StringDownload;





class StringDownloadTest extends \PHPUnit\Framework\TestCase
{
    public function testValue()
    {
        $v = new StringDownload('file content', 'f.txt');
        $this->assertEquals(true, $v->isSuccessful());
    }
    

    public function testOutput()
    {
        $this->expectOutputString('file content');
        $v = new StringDownload('file content', 'f.txt');
        $v->immediateOutput();      
        $this->assertEquals('file content', $v);
    }
    
 
}

?>