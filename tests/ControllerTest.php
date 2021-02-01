<?php

// no user namespace for this special test !


use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Controller;
use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Application;
use \Nettools\Simple_Framework\Registry;
use \Nettools\Simple_Framework\Config\ConfigObject;
use \Nettools\Core\ExceptionHandlers\SimpleExceptionHandler;




class TestCommandFailedException extends \Exception{}
class TestUnauthorizedCommandException extends \Exception{}




class TestExceptionHandler extends SimpleExceptionHandler
{
    public function handleException(\Throwable $e)
    {
        throw $e;
    }
}



class TestNoReturnCommand extends Command 
{
     public function execute(Request $req, Application $app)
     {
         return; // no value returned !
     }
    
}
    


class TestFailedCommand extends Command 
{
     public function execute(Request $req, Application $app)
     {
         $this->fail('failure !');
     }
    
}
    


class TestInvalidRequestCommand extends Command 
{
     public function validateRequest(Request $r)
     {
         return false;
     }

    
     public function execute(Request $req, Application $app)
     {
     }
    
}
    


class TestCommandForward extends Command 
{
     public function execute(Request $req, Application $app)
     {
         return $app->controller->forward(new TestForwardedCommand(), $req, $app);
     }
    
}
    


class TestForwardedCommand extends Command 
{
     public function execute(Request $req, Application $app)
     {
         return $this->returnString('forwarded');
     }
    
}
    






class ControllerTest extends \PHPUnit\Framework\TestCase
{
    protected $app;
    protected $controller_stub;
    
    
    public function setUp() :void
    {
        // mock abstract methods only and call default constructor with required parameters (no user namespace)
        $this->controller_stub = $this->getMockBuilder(Controller::class)
                    ->onlyMethods(['getRequest', 'handleCommandFailure', 'handleUnauthorizedCommand', '_outputValue'])
                    ->setConstructorArgs([''])->getMock();

        // mock method called when a command fails (CommandFailedException thrown by user)
        $this->controller_stub->method('handleCommandFailure')->will($this->throwException(new TestCommandFailedException('command failure')));
        $this->controller_stub->method('handleUnauthorizedCommand')->will($this->throwException(new TestUnauthorizedCommandException('command not authorized')));

        // create application
        $this->app = new Application(
                // controller
                $this->controller_stub, 
            
                // registry
                new Registry(
                    array(
                        // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                        'appcfg' => new ConfigObject((object)array(
                                            'application' => (object)array('exceptionHandler'=>TestExceptionHandler::class)
                                        ))
                    ))
            );
        
    }
    
    
    public function testNoCommand()
    {
        // this request has no CMD parameter, so the default library command (defaultCommand class) will be used (it returns a NULL value)
        $r = new Request(array('input0'=>'', 'input1'=>'value1'));
        $this->controller_stub->method('getRequest')->willReturn($r);
        
        $ret = $this->app->run();
        $this->assertEquals(NULL, $ret->getValue());
    }
    

	public function testInexistantCommand()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidCommandException::class);

		 
		 // this request will fail since the command does not exist
        $r = new Request(array('cmd'=>'not_existing_command', 'input0'=>'', 'input1'=>'value1'));
        $this->controller_stub->method('getRequest')->willReturn($r);
        
        $this->app->run();
    }
    
    

	public function testUndefinedReturnValue()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\UnknownReturnException::class);

		
		$r = new Request(array('cmd'=>'TestNoReturnCommand', 'input0'=>'', 'input1'=>'value1'));
        $this->controller_stub->method('getRequest')->willReturn($r);
        
        $this->app->run();
    }
    
    
    public function testFailedCommand()
    {
     	$this->expectException(TestCommandFailedException::class);

		
		$r = new Request(array('cmd'=>'TestFailedCommand', 'input0'=>'', 'input1'=>'value1'));
        $this->controller_stub->method('getRequest')->willReturn($r);
        
        $this->app->run();
    }
     
    

	public function testInvalidRequestCommand()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidRequestException::class);

		
		$r = new Request(array('cmd'=>'TestInvalidRequestCommand', 'input0'=>'', 'input1'=>'value1'));
        $this->controller_stub->method('getRequest')->willReturn($r);
        
        $this->app->run();
    }
     
    
    public function testForwardedCommand()
    {
        $r = new Request(array('cmd'=>'TestCommandForward', 'input0'=>'', 'input1'=>'value1'));
        $this->controller_stub->method('getRequest')->willReturn($r);
        
        $ret = $this->app->run();
        $this->assertEquals('forwarded', $ret->getValue());
    }
   
}

?>