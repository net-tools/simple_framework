<?php
/**
 * CSRFSecurityHandler
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */



namespace Nettools\Simple_Framework\SecurityHandlers;


use \Nettools\Core\Helpers\SecureRequestHelper;
use \Nettools\Core\Helpers\SecureRequestHelper\CSRFException;




/**
 * Class for a Request security handler with a hash value in request parameter
 */
class CSRFSecurityHandler extends SecurityHandler {


	/**
	 * @var \Nettools\Core\Helpers\SecureRequestHelper
	 */
	protected $_sec = NULL;
	
	
	
	/**
	 * Constructor
	 * 
	 * @param string $secret Secret string used to compute hash
	 * @param string $hparam Name of the Request object hash parameter (defaults to 'h')
	 * @param string $idparam Name of the Request object client id parameter (defaults to 'i')
	 */
	public function __construct($csrf_cookiename = '_CSRF_', $csrf_submittedvaluename = '_FORM_CSRF_')
	{
		$this->_sec = new SecureRequestHelper($csrf_cookiename, $csrf_submittedvaluename);
	}
	
	
	
	/**
	 * Get the underlying security helper from nettools/core lib
	 *
	 * @return \Nettools\Core\Helpers\SecureRequestHelper
	 */
	public function getSecureRequestHelper()
	{
		return $this->_sec;
	}
	
	
	
	/**
	 * Check the Request object and its _h_ value according to the client identifier (_id_ parameter)
	 *
	 * @param \Nettools\Simple_Framework\Request $req
	 * @return bool
	 */
	public function check(\Nettools\Simple_Framework\Request $req)
	{
		try 
		{
			$this->_sec->authorizeCSRF($req->getRequestAsArray());
			return true;
		}
		catch (CSRFException $e)
		{
			return false;
		}
	}
	
}



?>