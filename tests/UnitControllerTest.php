<?php

// no user namespace for this special test !


use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Controller;
use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Application;
use \Nettools\Simple_Framework\UnitTestApplication;
use \Nettools\Simple_Framework\Registry;
use \Nettools\Simple_Framework\Config\ConfigObject;
use \Nettools\Core\ExceptionHandlers\SimpleExceptionHandler;


 


class MyUnitTestCommand extends Command 
{
     public function execute(Request $req, Application $app)
     {
         return $this->returnString('Ok !');
     }
    
}
    



class MyUnitTestFailedCommand extends Command 
{
     public function execute(Request $req, Application $app)
     {
         $this->fail('error here');
     }
    
}
    




class UnitControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testFailedCommand()
    {
        // create application
        $app = UnitTestApplication::create(
				// user namespace
				'',
			
                // registry
                new Registry(
                    array(
                        // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                        'appcfg' => new ConfigObject((object)array(
                                            'application' => (object)array('exceptionHandler'=>SimpleExceptionHandler::class)
                                        ))
                    )),
			
				// command
				[
					'cmd'	=> 'MyUnitTestFailedCommand',
					'arg1'	=> 'Request value 1',
					'arg2'	=> 'Request value 2'
				]
            );

		
        $output = $app->run();
		$this->assertEquals('error here', $output->getValue());
		$this->assertEquals(false, $output->isSuccessful());
    }
     
	
    
    public function testCommand()
    {
        // create application
        $app = UnitTestApplication::create(
				// user namespace
				'',
			
                // registry
                new Registry(
                    array(
                        // define appcfg to set a custom exception handler (since the default one outputs error and headers to stdout)
                        'appcfg' => new ConfigObject((object)array(
                                            'application' => (object)array('exceptionHandler'=>SimpleExceptionHandler::class)
                                        ))
                    )),
			
				// command
				[
					'cmd'	=> 'MyUnitTestCommand',
					'arg1'	=> 'Request value 1',
					'arg2'	=> 'Request value 2'
				]
            );

		
        $output = $app->run();
        $this->assertEquals('Ok !', $output->getValue());
    }
   
}

?>