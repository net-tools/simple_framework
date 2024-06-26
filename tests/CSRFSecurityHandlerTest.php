<?php

// no user namespace for this special test !


use \Nettools\Core\Helpers\SecureRequestHelper\AbstractBrowserInterface;


class BI extends AbstractBrowserInterface 
{
	public function setCookie($name, $value, $expires, $domain) {}

	public function deleteCookie($name, $domain) {}

	public function getCookie($name) {}
}



    
class CSRFSecurityHandlerTest extends \PHPUnit\Framework\TestCase
{
    public function testSH()
    {
        $sh = new \Nettools\Simple_Framework\SecurityHandlers\CSRFSecurityHandler('__CSRF__', '__CSRF_VALUE__');
		
		$intf = $this->getMockBuilder(BI::class)->onlyMethods(['setCookie', 'deleteCookie', 'getCookie'])->getMock();
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
	 	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidParameterException::class);
	 	$this->expectExceptionMessage("Method 'testMethod' does not exists in security handler");

	
		$sh = new \Nettools\Simple_Framework\SecurityHandlers\CSRFSecurityHandler('__CSRF__', '__CSRF_VALUE__');
		
		$intf = $this->getMockBuilder(BI::class)->onlyMethods(['setCookie', 'deleteCookie', 'getCookie'])->getMock();
		$sh->getSecureRequestHelper()->setBrowserInterface($intf);
		
		$sh->testMethod();	// asserting an exception is thrown because testMethod is not a method of $sh nor its underlying CSRF layer object
    }

}

?>