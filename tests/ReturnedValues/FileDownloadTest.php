<?php

namespace Nettools\Simple_Framework\Tests;



use \Nettools\Simple_Framework\ReturnedValues\FileDownload;





class FileDownloadTest extends \PHPUnit\Framework\TestCase
{
    public function testValue()
    {
        $v = new FileDownload(__FILE__, 'f.php.txt');
        $this->assertEquals(true, $v->isSuccessful());
    }
    

    public function testOutput()
    {
        $v = new FileDownload(__FILE__, 'f.php.txt', 'application/php');
        $this->assertEquals(__FILE__, $v->getValue());
		$this->assertEquals('f.php.txt', $v->getFilename());
		$this->assertEquals('application/php', $v->getContentType());
    }
    
 
}

?>