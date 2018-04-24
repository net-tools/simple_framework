<?php
/**
 * UnitTestController
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework;



use \Nettools\Core\Helpers\SecurityHelper;



/**
 * Class for unit test application controller.
 * 
 * It sets the request object from constructor parameter 
 */
class UnitTestController extends Controller {
	
	/**
	 * @var Request Request for unit test
	 */
	protected $_req;
	
	

    /**
     * Get a request for command
     *
     * @return Request Returns a Request object
     */
    public function getRequest()
    {
		return $this->_req;
    }
    
    
	
    /** 
     * Handle command failure by user.
     *
     * We return an error message with an unsuccessful state.
     *
     * @param Exceptions\CommandFailedException $e
     * @return ReturnedValues\Value Returns a value representing the error, with an unsuccessful state
     */
    protected function handleCommandFailure(Exceptions\CommandFailedException $e)
    {
    	return new ReturnedValues\PHP($e->getMessage(), false);
    }
    
	
        
    /** 
     * Constructor of web controller
     *
     * @param string $ns Namespace used in the user application for commands
     */
    public function __construct($ns, Request $r)
    {
        parent::__construct($ns);
        $this->_req = $r;
    }
}



?>