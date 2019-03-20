<?php

// no user namespace for this special test !


use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Controller;
use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Application;
use \Nettools\Simple_Framework\Registry;
use \Nettools\Simple_Framework\Config\ConfigObject;
use \Nettools\Core\ExceptionHandlers\SimpleExceptionHandler;
use \Nettools\Core\Helpers\SecureRequestHelper\AbstractBrowserInterface;



    
class CSRFSecurityHandlerTest extends \PHPUnit\Framework\TestCase
{
    public function testSH()
    {
        $sh = new \Nettools\Simple_Framework\SecurityHandlers\CSRFSecurityHandler('__CSRF__', '__CSRF_VALUE__');
		
		$intf = $this->getMockForAbstractClass(AbstractBrowserInterface::class);
		$intf->expects($this->once())->method('getCookie')->willReturn('abcdef');
		$intf->expects($this->once())->method('deleteCookie');
		$intf->expects($this->once())->method('setCookie');
		$sh->getSecureRequestHelper()->setBrowserInterface($intf);
		
		// asserting that CSRFSecurityHandler act as a proxy to its underlying SecureRequestHelper object
		$this->assertEquals('abcdef', $sh->getCSRFCookie());
		$this->assertEquals('__CSRF__', $sh->getCSRFCookieName());
		$this->assertEquals('__CSRF_VALUE__', $sh->getCSRFSubmittedValueName());
		$sh->revokeCSRF();		// asserting deleteCookie is called in $intf stub
		$sh->initializeCSRF();	// asserting setCookie is called in $intf stub
    }
    
	
    public function testMethodKo()
    {
		// TODO : should be \Nettools\Simple_Framework\Exceptions\InvalidParameterException;
	 	$this->expectException(\Error::class);
	 	//$this->expectedExceptionMessage("Method 'testMethod' does not exists in security handler");

	
		$sh = new \Nettools\Simple_Framework\SecurityHandlers\CSRFSecurityHandler('__CSRF__', '__CSRF_VALUE__');
		
		$intf = $this->getMockForAbstractClass(AbstractBrowserInterface::class);
		$sh->getSecureRequestHelper()->setBrowserInterface($intf);
		
		$sh->testMethod();	// asserting an exception is thrown because testMethod is not a method of $sh nor its underlying CSRF layer object
    }

}

?>