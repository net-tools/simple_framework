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
        $this->expectOutputString(file_get_contents(__FILE__));
        $v = new FileDownload(__FILE__, 'f.php.txt');
        $v->immediateOutput();
        $this->assertEquals(file_get_contents(__FILE__), $v);
    }
    
 
}

?>