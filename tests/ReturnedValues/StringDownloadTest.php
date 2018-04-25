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
        $v = new StringDownload('file content', 'f.txt', 'text/plain');
        $this->assertEquals('file content', $v->getValue());
		$this->assertEquals('f.txt', $v->getFilename());
		$this->assertEquals('text/plain', $v->getContentType());
    }
    
 
}

?>