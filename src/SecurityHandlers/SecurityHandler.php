<?php
/**
 * SecurityHandler
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\SecurityHandlers;



/**
 * Base class for a Request security handler
 *
 * Can be implemented with hash values in request parameters and/or CSRF or with a dummy handler
 */
abstract class SecurityHandler {

	/**
	 * Check the Request object
	 *
	 * @param \Nettools\Simple_Framework\Request $req
	 * @return bool
	 */
	abstract public function check(\Nettools\Simple_Framework\Request $req);
	
	
	
	/**
	 * Initialize the security handler
	 *
	 * @param string[] Initialize context
	 */
	abstract public function initialize(array &$context);
}



?>