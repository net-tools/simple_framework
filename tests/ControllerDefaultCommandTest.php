<?php


namespace NT\Tests {

    class TestNamespacedDefaultCommand extends \Nettools\Simple_Framework\Command 
    {
         public function execute(\Nettools\Simple_Framework\Request $req, \Nettools\Simple_Framework\Application $app)
         {
             return $this->returnPHP('NS default command');
         }
    }

    
}




namespace Nettools\Simple_Framework\Tests {
    
    use \Nettools\Simple_Framework\Request;
    use \Nettools\Simple_Framework\Controller;
    use \Nettools\Simple_Framework\Command;
    use \Nettools\Simple_Framework\Application;
    use \Nettools\Simple_Framework\Registry;
    use \Nettools\Simple_Framework\Config\ConfigObject;
    use \Nettools\Core\ExceptionHandlers\SimpleExceptionHandler;

    
    
    class TestNSDefaultCommandFailedException extends \Exception{}



    class TestNSDefaultExceptionHandler extends SimpleExceptionHandler
    {
        public function handleException(\Throwable $e)
        {
            throw $e;
        }
    }






    class NSDefaultControllerTest extends \PHPUnit\Framework\TestCase
    {
        protected $app;
        protected $controller_stub;


        public function setUp()
        {
            // mock abstract methods only and call default constructor with required parameters (app and user namespace)
            $this->controller_stub = $this->getMockBuilder(Controller::class)
                        ->setMethods(['getRequest', 'handleCommandFailure'])
                        ->setConstructorArgs(['\\NT\\Tests'])->getMock();

            // mock method called when a command fails (CommandFailedException thrown by user)
            $this->controller_stub->method('handleCommandFailure')->will($this->throwException(new TestNSDefaultCommandFailedException('command failure')));


            // create application
            $this->app = new Application(
                    // controller
                    $this->controller_stub, 

                    // registry
                    new Registry(
                        array(
                            // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                            'appcfg' => new ConfigObject((object)array(
                                                'application' => (object)array('exceptionHandler'=>TestNSDefaultExceptionHandler::class),
                                                'controller' => (object)array('userDefaultCommand'=>'TestNamespacedDefaultCommand')
                                            ))
                        ))
                );
    
        }


        // this request has no CMD parameter, but the controller config has a directive for the default command class
        public function testNoCommand()
        {
            $r = new Request(array('input0'=>'', 'input1'=>'value1'));
            $this->controller_stub->method('getRequest')->willReturn($r);

            $ret = $this->app->run();
            $this->assertEquals('NS default command', $ret->getValue());
        }

    }
}
?>