<?php
/**
 * WebController
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework;



use \Nettools\Core\Helpers\SecurityHelper;



/**
 * Class for web application controller.
 * 
 * It extracts Request from $_REQUEST PHP array, file uploads from $_FILES array, and command name is specified in the reserved CMD parameter.
 */
class WebController extends Controller {

    /**
     * @var string HTTP verb (GET, POST, etc.)
     */
    protected $_http_verb = NULL;
    
        
    
    /**
     * Get HTTP verb (POST, GET, etc.)
     *
     * @return string HTTP verb used for request
     */
    public function getHttpVerb()
    {
        return $this->_http_verb;
    }
    
    
    /**
     * Get a request for command, extracted from $_REQUEST and $_FILES PHP arrays.
     *
     * @return Request Returns a Request object
     */
    protected function getRequest()
    {
        SecurityHelper::sanitize_array($_REQUEST);
        
        $files = array();
		foreach ( $_FILES as $k=>$file )
			$files[$k] = new FileUploadRequest($file['error'], $file['tmp_name'], $file['size'], $file['type'], $file['name']);
        
        return new Request($_REQUEST, $files);
    }
    
    
    /** 
     * Handle command failure by user.
     *
     * If request is sent from XMLHTTPREQUEST, we set a JSON-formatted return value ; otherwise, we return a string with the error
     * message. In both case, the returned value is set with an unsuccessful state.
     *
     * @param Exceptions\CommandFailedException $e
     * @return ReturnedValues\Value Returns a value representing the error, with an unsuccessful state
     */
    protected function handleCommandFailure(Exceptions\CommandFailedException $e)
    {
        // si appel xmlhttp
        if ( (strpos($_SERVER['HTTP_USER_AGENT'], 'XMLHTTP') === 0) || (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') === 0) )
            return new ReturnedValues\Json(json_encode(array('statut'=>false, 'message'=>$e->getMessage())), false);
        else
            return new ReturnedValues\PHP($e->getMessage(), false);
    }
    
        
    /** 
     * Constructor of web controller
     *
     * @param string $ns Namespace used in the user application for commands
     */
    public function __construct($ns)
    {
        parent::__construct($ns);
        
        $this->_http_verb = $_SERVER['REQUEST_METHOD'];
    }
}



?>