<?php
/**
 * HashSecurityHandler
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\SecurityHandlers;



/**
 * Class for a Request security handler with a hash value in request parameter
 */
class HashSecurityHandler extends SecurityHandler {

	protected $_secret = NULL;
	protected $_hparam = NULL;
	protected $_idparam = NULL;
	
	
	
	/**
	 * Constructor
	 * 
	 * @param string $secret Secret string used to compute hash
	 * @param string $hparam Name of the Request object hash parameter (defaults to 'h')
	 * @param string $idparam Name of the Request object client id parameter (defaults to 'i')
	 */
	public function __construct($secret, $hparam = 'h', $idparam = 'i')
	{
		$this->_secret = $secret;
		$this->_hparam = $hparam;
		$this->_idparam = $idparam;
	}
	
	
	
	/**
	 * Create a hash value based on an ID (usually the client identifier, such as an email or db id) and a secret
	 * 
	 * @param string $id
	 * @param string $secret
	 * @return string
	 */
	public static function makeHash($id, $secret)
	{
		return hash('sha256', $id . '!' . $secret);
	}
	
	
	
	/**
	 * Check the Request object and its _h_ value according to the client identifier (_id_ parameter)
	 *
	 * @param \Nettools\Simple_Framework\Request $req
	 * @return bool
	 */
	public function check(\Nettools\Simple_Framework\Request $req)
	{
		return $req->{$this->_hparam} == self::makeHash($req->{$this->_idparam}, $this->_secret);
	}
	
}



?>