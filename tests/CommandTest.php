<?php

namespace Nettools\Simple_Framework\Tests;




use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Application;




class Test extends Command
{
     public function execute(Request $req, Application $app)
     {
         
     }
}



class TestInvalidRequest extends Command
{
     public function validateRequest(Request $req)
     {
         return false;
     }
    
    
     public function execute(Request $req, Application $app)
     {
     }
}



class TestFail extends Command
{
     public function execute(Request $req, Application $app)
     {
         $this->fail('no success');
     }
}



class TestReturnString extends Command
{
     public function execute(Request $req, Application $app)
     {
         return $this->returnString('returned string');
     }
}



class TestReturnHTML extends Command
{
     public function execute(Request $req, Application $app)
     {
         return $this->returnHTML('<b>bold</b>');
     }
}



class TestReturnFloat extends Command
{
     public function execute(Request $req, Application $app)
     {
         return $this->returnFloat(123.456);
     }
}



class TestReturnBool extends Command
{
     public function execute(Request $req, Application $app)
     {
         return $this->returnBool(true);
     }
}



class TestReturnNull extends Command
{
     public function execute(Request $req, Application $app)
     {
         return $this->returnNull();
     }
}



class TestReturnInt extends Command
{
     public function execute(Request $req, Application $app)
     {
         return $this->returnInt(1234);
     }
}



class TestReturnStringDownload extends Command
{
     public function execute(Request $req, Application $app)
     {
         return $this->returnStringDownload('returned download', 'file.txt');
     }
}



class TestReturnFileDownload extends Command
{
     public function execute(Request $req, Application $app)
     {
         return $this->returnFileDownload(__FILE__, 'file.php.txt');
     }
}



class TestReturnJson extends Command
{
     public function execute(Request $req, Application $app)
     {
         return $this->returnJson('{"prop":"value"}');
     }
}





class CommandTest extends \PHPUnit\Framework\TestCase
{
    public function testAbstractCommand()
    {
        $r = new Request(array('input0'=>'', 'input1'=>'value1', 'input2'=>'12'));
        $c = new Test();
        
        $this->assertEquals('test', $c->getCommandName());
        $this->assertEquals(true, $c->validateRequest($r));
    }
    
    
    /**
     * @expectedException \Nettools\Simple_Framework\Exceptions\CommandFailedException
     */
    public function testFail()
    {
        $r = new Request(array('input0'=>'', 'input1'=>'value1', 'input2'=>'12'));
        $c = new TestFail();
        
        $app_stub = $this->createMock(Application::class);
        
        $c->execute($r, $app_stub);
    }

    
    public function testInvalidRequest()
    {
        $r = new Request(array('input0'=>'', 'input1'=>'value1', 'input2'=>'12'));
        $c = new TestInvalidRequest();
        
        $this->assertEquals(false, $c->validateRequest($r));
    }

    
    public function testReturns()
    {
        $r = new Request(array('input0'=>'', 'input1'=>'value1', 'input2'=>'12'));
        $app_stub = $this->createMock(Application::class);
        $this->assertEquals('returned string', (new TestReturnString())->execute($r, $app_stub));       // with value->__toString()
        $this->assertEquals('returned string', (new TestReturnString())->execute($r, $app_stub)->getValue());       // direct access to PHP returned value
        $this->assertEquals('{"prop":"value"}', (new TestReturnJson())->execute($r, $app_stub));
        $this->assertEquals('returned download', (new TestReturnStringDownload())->execute($r, $app_stub));
        $this->assertEquals(file_get_contents(__FILE__), (new TestReturnFileDownload())->execute($r, $app_stub));
        $this->assertEquals(123.456, (new TestReturnFloat())->execute($r, $app_stub)->getValue());
        $this->assertEquals(true, (new TestReturnBool())->execute($r, $app_stub)->getValue());
        $this->assertEquals(null, (new TestReturnNull())->execute($r, $app_stub)->getValue());
        $this->assertEquals(1234, (new TestReturnInt())->execute($r, $app_stub)->getValue());
        $this->assertEquals('<b>bold</b>', (new TestReturnHTML())->execute($r, $app_stub)->getValue());
    }
        
}

?>