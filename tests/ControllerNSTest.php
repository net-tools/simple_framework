<?php


namespace NT\Tests {

    class TestNamespacedCommand extends \Nettools\Simple_Framework\Command 
    {
         public function execute(\Nettools\Simple_Framework\Request $req, \Nettools\Simple_Framework\Application $app)
         {
             return $this->returnPHP('NS command');
         }
    }

    
}





namespace Nettools\Simple_Framework\Tests{

    use \Nettools\Simple_Framework\Request;
    use \Nettools\Simple_Framework\Controller;
    use \Nettools\Simple_Framework\Application;
    use \Nettools\Simple_Framework\Registry;
    use \Nettools\Simple_Framework\Config\ConfigObject;
    use \Nettools\Core\ExceptionHandlers\SimpleExceptionHandler;

    
    
    class TestNSCommandFailedException extends \Exception{}
    class TestNSUnauthorizedCommandException extends \Exception{}



    class TestNSExceptionHandler extends SimpleExceptionHandler
    {
        public function handleException(\Throwable $e)
        {
            throw $e;
        }
    }






    class NSControllerTest extends \PHPUnit\Framework\TestCase
    {
        protected $app;
        protected $controller_stub;


        public function setUp() :void
        {
            // mock abstract methods only and call default constructor with required parameters (app and user namespace)
            $this->controller_stub = $this->getMockBuilder(Controller::class)
                        ->onlyMethods(['getRequest', 'handleCommandFailure', 'handleUnauthorizedCommand', '_outputValue'])
                        ->setConstructorArgs(['\\NT\\Tests'])->getMock();

            // mock method called when a command fails (CommandFailedException thrown by user)
            $this->controller_stub->method('handleCommandFailure')->will($this->throwException(new TestNSCommandFailedException('command failure')));
        	$this->controller_stub->method('handleUnauthorizedCommand')->will($this->throwException(new TestNSUnauthorizedCommandException('command not authorized')));


            // create application
            $this->app = new Application(
                    // controller
                    $this->controller_stub, 

                    // registry
                    new Registry(
                        array(
                            // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                            'appcfg' => new ConfigObject((object)array(
                                                'application' => (object)array('exceptionHandler'=>TestNSExceptionHandler::class)
                                            ))
                        ))
                );
    
        }


        public function testNamespacedInexistantCommand()
        {
			$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidCommandException::class);
			
			
            $r = new Request(array('cmd'=>'TestNamespacedInexistantCommand', 'input0'=>'', 'input1'=>'value1'));
            $this->controller_stub->method('getRequest')->willReturn($r);

            $this->app->run();
        }


        public function testNamespacedCommand()
        {
            $r = new Request(array('cmd'=>'TestNamespacedCommand', 'input0'=>'', 'input1'=>'value1'));
            $this->controller_stub->method('getRequest')->willReturn($r);

            $ret = $this->app->run();
            $this->assertEquals('NS command', $ret->getValue());
        }

    }
}
?>