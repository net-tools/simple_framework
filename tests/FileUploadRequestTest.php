<?php

namespace Nettools\Simple_Framework\Tests;




use \Nettools\Simple_Framework\FileUploadRequest;




class FileUploadRequestTest extends \PHPUnit\Framework\TestCase
{
    public function testWrongParameters()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidParameterException::class);
		 
		 
        $r = new FileUploadRequest('/tmp/abc', UPLOAD_ERR_OK, 100, 'image/jpeg', 'myfile.txt');      // parameter 1 must be an int (upload error code)
    }

    
    public function testWrongParameters2()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidParameterException::class);

		
		$r = new FileUploadRequest(UPLOAD_ERR_OK, 100, '/tmp/abc', 'image/jpeg', 'myfile.txt');      // parameter 2 must be a string (path to temp file uploaded)
    }

    
    public function testWrongParameters3()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidParameterException::class);

		
		$r = new FileUploadRequest(UPLOAD_ERR_OK, '/tmp/abc', 'image/jpeg', 100, 'myfile.txt');      // parameter 3 must be an int (size of upload)
    }

    
    public function testWrongParameters4()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidParameterException::class);

		
		$r = new FileUploadRequest(UPLOAD_ERR_OK, '/tmp/abc', 100, 0, 'myfile.txt');                // parameter 4 must be a string (mime type)
    }

    
    public function testWrongParameters5()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidParameterException::class);

		
		$r = new FileUploadRequest(UPLOAD_ERR_OK, '/tmp/abc', 100, 'image/jpeg', 500);              // parameter 5 must be a string (filename on client computer)
    }

    
    public function testFileUploadRequest()
    {
        $r = new FileUploadRequest(UPLOAD_ERR_OK, '/tmp/abc', 100, 'image/jpeg', 'myfile.txt');
        $this->assertEquals(UPLOAD_ERR_OK, $r->error);
        $this->assertEquals('/tmp/abc', $r->tmp_name);
        $this->assertEquals(100, $r->size);
        $this->assertEquals('image/jpeg', $r->type);
        $this->assertEquals('myfile.txt', $r->name);
    }

    
    public function testStatus()
    {
        $r = new FileUploadRequest(UPLOAD_ERR_OK, '/tmp/abc', 100, 'image/jpeg', 'myfile.txt');
        $this->assertEquals(true, $r->uploaded());
        $this->assertEquals(false, $r->no_file());
        $this->assertEquals(true, $r->success());

        $r = new FileUploadRequest(UPLOAD_ERR_NO_FILE, '', '', '', '');
        $this->assertEquals(false, $r->uploaded());
        $this->assertEquals(true, $r->no_file());
        $this->assertEquals(true, $r->success());

        $r = new FileUploadRequest(UPLOAD_ERR_NO_FILE, '', '', '', '');
        $this->assertEquals(false, $r->uploaded());
        $this->assertEquals(true, $r->no_file());
        $this->assertEquals(true, $r->success());
        
        $r = new FileUploadRequest(UPLOAD_ERR_PARTIAL, '', '', '', '');
        $this->assertEquals(false, $r->uploaded());
        $this->assertEquals(false, $r->no_file());
        $this->assertEquals(false, $r->success());
        
        
    }
    

}

?>