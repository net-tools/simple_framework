<?php

// no user namespace for this special test !


use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Controller;
use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\AuthenticatedCommand;
use \Nettools\Simple_Framework\Application;
use \Nettools\Simple_Framework\Registry;
use \Nettools\Simple_Framework\Config\ConfigObject;
use \Nettools\Core\ExceptionHandlers\SimpleExceptionHandler;




class TestSHCommandFailedException extends \Exception{}
class TestSHUnauthorizedCommandException extends \Exception{}




class TestSHExceptionHandler extends SimpleExceptionHandler
{
    public function handleException(\Throwable $e)
    {
        throw $e;
    }
}





abstract class SHController extends \Nettools\Simple_Framework\Controller{
	
	public function getHandlers(Application $app)
	{
		return $this->getSecurityHandlers($app);
	}
	
}


class TestAuthenticatedCommand extends AuthenticatedCommand 
{
    public function execute(Request $req, Application $app)
    {
		return $this->returnPHP('');
    }
    
}

    
class TestUnauthenticatedCommand extends Command 
{
    public function execute(Request $req, Application $app)
    {
		return $app->controller->forward(new TestAuthenticatedCommand(), $req, $app);
    }
    
}
    









class SHControllerTest extends \PHPUnit\Framework\TestCase
{
    protected $controller_stub;
    
    
    public function setUp()
    {
        // mock abstract methods only and call default constructor with required parameters (no user namespace)
        $this->controller_stub = $this->getMockBuilder(SHController::class)
                    ->setMethods(['getRequest', 'handleCommandFailure', 'handleUnauthorizedCommand', '_outputValue'])
                    ->setConstructorArgs([''])->getMock();
		
        // mock method called when a command fails (CommandFailedException thrown by user)
        $this->controller_stub->method('handleCommandFailure')->will($this->throwException(new TestSHCommandFailedException('command failure')));
        $this->controller_stub->method('handleUnauthorizedCommand')->will($this->throwException(new TestSHUnauthorizedCommandException('command not authorized')));
    }
    
    
    public function testSH()
    {

        // create application
        $app = new Application(
                // controller
                $this->controller_stub, 
            
                // registry
                new Registry(
                    array(
                        // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                        'appcfg' => new ConfigObject((object)array(
                                            'controller' => (object)array(
																'userSecurityHandlers' => (object)[
																	'HashSecurityHandler' => ['secret_here', '_h', '_i']
																]
                                                            )
                                        ))
                    ))
            );

		
		// this request has no CMD parameter, so the default library command (defaultCommand class) will be used (it returns a NULL value)
        $this->assertEquals(1, count($this->controller_stub->getHandlers($app)));
		$this->assertInstanceOf(\Nettools\Simple_Framework\SecurityHandlers\HashSecurityHandler::class, $this->controller_stub->getHandlers($app)[0]);
    }
    

	
	/**
	 * @expectedException TestSHUnauthorizedCommandException
	 * @expectedExceptionMessage command not authorized
	 */
    public function testSHCheckKo()
    {

        // create application
        $app = new Application(
                // controller
                $this->controller_stub, 
            
                // registry
                new Registry(
                    array(
                        // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                        'appcfg' => new ConfigObject((object)array(
											'application' => (object)array('exceptionHandler'=>TestSHExceptionHandler::class),
                                            'controller' => (object)array(
																'userSecurityHandlers' => (object)[
																	'HashSecurityHandler' => ['secret_here', '_h', '_i']
																]
                                                            )
                                        ))
                    ))
            );

		
		// run will fail since there's no _h and _i parameter in request
        $r = new Request(array('cmd'=>'TestAuthenticatedCommand', 'input0'=>'', 'input1'=>'value1'));
        $this->controller_stub->method('getRequest')->willReturn($r);
        $ret = $app->run();
		$this->assertEquals(false, $ret->isSuccessful());
    }
    

	
	/**
	 * @expectedException TestSHUnauthorizedCommandException
	 * @expectedExceptionMessage command not authorized
	 */
    public function testSHCheckForwardKo()
    {

        // create application
        $app = new Application(
                // controller
                $this->controller_stub, 
            
                // registry
                new Registry(
                    array(
                        // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                        'appcfg' => new ConfigObject((object)array(
											'application' => (object)array('exceptionHandler'=>TestSHExceptionHandler::class),
                                            'controller' => (object)array(
																'userSecurityHandlers' => (object)[
																	'HashSecurityHandler' => ['secret_here', '_h', '_i']
																]
                                                            )
                                        ))
                    ))
            );

		
        $r = new Request(array(
				'cmd'	=>'TestUnauthenticatedCommand'
			));
        $this->controller_stub->method('getRequest')->willReturn($r);
        $ret = $app->run();
	}
    

	
    public function testSHCheckForwardOk()
    {

        // create application
        $app = new Application(
                // controller
                $this->controller_stub, 
            
                // registry
                new Registry(
                    array(
                        // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                        'appcfg' => new ConfigObject((object)array(
											'application' => (object)array('exceptionHandler'=>TestSHExceptionHandler::class),
                                            'controller' => (object)array(
																'userSecurityHandlers' => (object)[
																	'HashSecurityHandler' => ['secret_here', '_h', '_i']
																]
                                                            )
                                        ))
                    ))
            );

		
        $r = new Request(array(
				'cmd'	=>'TestUnauthenticatedCommand',
				'_h'	=> \Nettools\Simple_Framework\SecurityHandlers\HashSecurityHandler::makeHash('ID CLIENT', 'secret_here'),
				'_i'	=> 'ID CLIENT'
			));
        $this->controller_stub->method('getRequest')->willReturn($r);
        $ret = $app->run();
		
		// successful check because inside command we forward the process to AuthenticatedCommand, which require authentication, provided here
		$this->assertEquals(true, true);
	}
    

	
    public function testSHCheckOk()
    {

        // create application
        $app = new Application(
                // controller
                $this->controller_stub, 
            
                // registry
                new Registry(
                    array(
                        // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                        'appcfg' => new ConfigObject((object)array(
											'application' => (object)array('exceptionHandler'=>TestSHExceptionHandler::class),
                                            'controller' => (object)array(
																'userSecurityHandlers' => (object)[
																	'HashSecurityHandler' => ['secret_here', '_h', '_i']
																]
                                                            )
                                        ))
                    ))
            );

		
        $r = new Request(array(
				'cmd'	=>'TestAuthenticatedCommand', 
				'_h'	=> \Nettools\Simple_Framework\SecurityHandlers\HashSecurityHandler::makeHash('ID CLIENT', 'secret_here'),
				'_i'	=> 'ID CLIENT'
			));
        $this->controller_stub->method('getRequest')->willReturn($r);
        $ret = $app->run();
		
		// successfull check because _h and _i parameters exist in request
		$this->assertEquals(true, true);
	}
    

	
    public function testSHCheckOkDefaultParameters()
    {

        // create application
        $app = new Application(
                // controller
                $this->controller_stub, 
            
                // registry
                new Registry(
                    array(
                        // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                        'appcfg' => new ConfigObject((object)array(
											'application' => (object)array('exceptionHandler'=>TestSHExceptionHandler::class),
                                            'controller' => (object)array(
																'userSecurityHandlers' => (object)[
																	'HashSecurityHandler' => ['secret_here']
																]
                                                            )
                                        ))
                    ))
            );

		
        $r = new Request(array(
				'cmd'=>'TestAuthenticatedCommand', 
				'h'	=> \Nettools\Simple_Framework\SecurityHandlers\HashSecurityHandler::makeHash('ID CLIENT', 'secret_here'),
				'i'	=> 'ID CLIENT'
			));
        $this->controller_stub->method('getRequest')->willReturn($r);
        $ret = $app->run();
		
		// successfull check because h and i parameters exist in request
		$this->assertEquals(true, true);
	}
    

	
	/**
	 * @expectedException TestSHUnauthorizedCommandException
	 * @expectedExceptionMessage command not authorized
	 */
    public function testSHCheckKoRequestModified()
    {
        // create application
        $app = new Application(
                // controller
                $this->controller_stub, 
            
                // registry
                new Registry(
                    array(
                        // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                        'appcfg' => new ConfigObject((object)array(
											'application' => (object)array('exceptionHandler'=>TestSHExceptionHandler::class),
                                            'controller' => (object)array(
																'userSecurityHandlers' => (object)[
																	'HashSecurityHandler' => ['secret_here', '_h', '_i']
																]
                                                            )
                                        ))
                    ))
            );

		
		
		// run will fail because the _h value has been modified
        $r = new Request(array(
				'cmd'	=> 'TestAuthenticatedCommand', 
				'_h'	=> \Nettools\Simple_Framework\SecurityHandlers\HashSecurityHandler::makeHash('OTHER ID CLIENT', 'secret_here'),
				'_i'	=> 'ID CLIENT'
			));
        $this->controller_stub->method('getRequest')->willReturn($r);
        $ret = $app->run();
	}
    

	
	/**
	 * @expectedException \Nettools\Simple_Framework\Exceptions\InvalidSecurityHandlerException
	 */
    public function testSHError()
    {

        // create application
        $app = new Application(
                // controller
                $this->controller_stub, 
            
                // registry
                new Registry(
                    array(
                        // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                        'appcfg' => new ConfigObject((object)array(
                                            'controller' => (object)array(
																'userSecurityHandlers' => (object)[
																	'UnknownSecurityHandler' => []
																]
                                                            )
                                        ))
                    ))
            );

		
		// this request has no CMD parameter, so the default library command (defaultCommand class) will be used (it returns a NULL value)
        $this->controller_stub->getHandlers($app);
    }
    

	
    public function testNoSH()
    {
		// create application
        $app = new Application(
                // controller
                $this->controller_stub, 
            
                // registry
                new Registry(
                    array(
                        // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                        'appcfg' => new ConfigObject((object)array(
                                            'controller' => (object)array()
                                        ))
                    ))
            );

		
		// this request has no CMD parameter, so the default library command (defaultCommand class) will be used (it returns a NULL value)
        $this->assertEquals(0, count($this->controller_stub->getHandlers($app)));
    }
    
}

?>