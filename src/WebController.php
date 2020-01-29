<?php
/**
 * WebController
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework;



use \Nettools\Core\Helpers\SecurityHelper;



/**
 * Class for web application controller.
 * 
 * It extracts Request from $_REQUEST PHP array, file uploads from $_FILES array
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
    public function getRequest()
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
     * Handle command unauthorized exception
     *
     * @return ReturnedValues\Value Returns a value representing the unauthorized command error, with an unsuccessful state
     */
    protected function handleUnauthorizedCommand()
	{
        // si appel xmlhttp
        if ( (strpos($_SERVER['HTTP_USER_AGENT'], 'XMLHTTP') === 0) || (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') === 0) )
            return new ReturnedValues\Json(json_encode(array('statut'=>false, 'message'=>'Request is not authorized.')), false);
        else
            return new ReturnedValues\PHP('Request is not authorized.', false);
	}
	
	
	
	/** 
	 * At browser reception of output, automatically send a POST command.
	 *
	 * Useful to convert a GET request to a POST request immediately ; we can also use that after successful authentication of user
	 * to add IDs, hash or CSRF values in the request.
	 *
	 * @param string $cmd Command name to execute
	 * @param string[] $postData Associative array describing request parameters
	 */	 
	public function sendPOST($cmd, array $postData = [])
	{
		?>
		<html>
			<head>
			</head>
			<body>
				<form method="post" action="<?php echo $_SERVER['SCRIPT_URL'];?>" style="display: none; visibility: hidden;">
					<input type="hidden" name="cmd" value="<?php echo $cmd; ?>">
					<?php
					foreach ( $postData as $k => $v )
						echo "<input type=\"hidden\" name=\"$k\" value=\"" . htmlspecialchars($v) . "\">";
					?>
				</form>
				<script>
				document.forms[0].submit();
				</script>
			</body>
		</html>
		<?php
		
		// halting script here
		die();
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
	
	
	
	/** 
	 * Do login process
	 *
	 * @param string[] Associative array of parameters/values describing the login context ; may include a user ID, for example
	 * @param string Command name to redirect the login process to (useful to include security parameters in querystring/post data which don't exist at the moment in the request) ; if NULL, no redirect is done
	 */
	public function login(array $context, $redirectCommand = NULL)
	{
		// call parent login method ; in Controller class, context parameter is not modified, but returned (if it's modified, we can't used an on-the-fly array when calling 'login')
		$context = parent::login($context);
		
		
		// if we want to redirect after login
		if ( $redirectCommand )
			$this->sendPOST($redirectCommand, $context);
	}
	
	
	
	/** 
	 * Output a value
	 *
	 * @param ReturnedValues\Value $value
	 */
	protected function _outputValue(ReturnedValues\Value $value)
	{
		$ns = '\\' . trim(__NAMESPACE__,'\\') . '\\';
		$outputhandler_class = $ns . 'ReturnedValuesOutput\\WebController_' . substr(strrchr(get_class($value),'\\'),1);
		
		if ( class_exists($outputhandler_class) )
			$outputhandler_class::output($value);
	}
}



?>