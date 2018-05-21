<?php
/**
 * HashSecurityHandler
 *
 * @author Pierre - dev@nettools.ovh
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
	 * Initialize the security handler
	 *
	 * @param string[] Initialize context
	 */
	public function initialize(array &$context)
	{
		// add a parameter in context
		$context[$this->_hparam] = $this->makeHash($context[$this->_idparam], $this->_secret);
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
		$t = $req->{$this->_hparam};
		if ( is_null($t) )
			$t = '';
		
		return hash_equals(self::makeHash($req->{$this->_idparam}, $this->_secret), $t);
	}
	
}



?>