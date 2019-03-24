<?php
/**
 * Controller
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework;




/**
 * Base class for application controller ; command class is defined from the CMD request parameter
 *
 * In app registry, the following parameters are defined :
 *
 * - controller.userDefaultCommand : default command in user namespace if no command in request
 *
 */
abstract class Controller {
    
    /**
     * @var string User namespace for commands
     */
    protected $_commandsNamespace = NULL;
	
	
	
	/** 
	 * @var bool Has the current request passed the authentication process ? If no authentication handlers, we assume it passed it
	 */
	protected $_authenticationPassed = false;
    
    

	/** 
	 * @var bool Does the current command require authentication ?
	 */
	protected $_authenticationRequired = false;
	
	
	/**
	 * @var SecurityHandlers\SecurityHandler[] Array of security handlers
	 */
	protected $_securityHandlers = NULL;
    
    

    /** 
     * Create the Request object
     *
     * @return Request Returns a Request object for command parameters
     */
    abstract function getRequest();
	
	
	
	/** 
	 * Check if authentication process has passed
	 *
	 * @return bool Returns true if the request has been authenticated (through its parameters or if the command is flagged as not authenticated)
	 */
	public function authenticationPassed()
	{
		return $this->_authenticationPassed;
	}
    
    
	
	/** 
	 * Check if authentication is needed
	 *
	 * @return bool Returns true if the command requires authentication
	 */
	public function authenticationRequired()
	{
		return $this->_authenticationRequired;
	}
    
    
	
    /** 
     * Constructor of controller
     *
     * @param string $ns Namespace used in the user application for commands
     */
    public function __construct($ns)
    {
        $this->_commandsNamespace = $ns;
    }
    
    
	
    /**
     * Get a command object, whose class is built from the CMD reserved parameter
     *
     * @param Request $req
     * @param Application $app Application object (used to retrieve config data)
     * @return Command Returns a command object for this request
     * @throws Exceptions\InvalidCommandException Thrown if command class cannot be found
     */
    protected function getCommand(Request $req, Application $app)
    {
        $cmd = $req->cmd;
        $ns = $this->_commandsNamespace;
        
        
        // if command not in request (CMD parameter not set)
        if ( !$cmd )
            // if no app config directive for default command
            if ( !$app->registry->exists('appcfg') || !$app->registry->appcfg->controller || !($cmd = $app->registry->appcfg->controller->userDefaultCommand) )
            {
                $ns = __NAMESPACE__;
                $cmd = 'defaultCommand';
            }

        $class = $ns . '\\' . ucfirst($cmd);
        
        if ( !class_exists($class) )
            throw new Exceptions\InvalidCommandException("Class '$class' for command '$cmd' does not exist.");
        
        return new $class();       
    }
    
    
	
    /**
     * Get the security handler objects list
     *
     * @param Application $app Application object (used to retrieve config data)
     * @return SecurityHandlers\SecurityHandler[] Returns a SecurityHandler list
     * @throws Exceptions\InvalidCommandException Thrown if command class cannot be found
     */
    protected function getSecurityHandlers(Application $app)
    {
		// see if already cached
		if ( !is_null($this->_securityHandlers) )
			return $this->_securityHandlers;
		
		
		// if no app config directive for securityhandlers list
		if ( !$app->registry->exists('appcfg') || !$app->registry->appcfg->controller || !($handlers_obj = $app->registry->appcfg->controller->userSecurityHandlers) )
			$handlers_obj = (object)[];

		
		// transform the property values (array of constructor parameters) to security handler object instance
		$ret = [];
		foreach( $handlers_obj as $handlerClass => $hparams )
		{
			// if security handler classname is absolute (with namespace), no need to add the lib namespace
			if ( !(strpos($handlerClass,'\\') === 0) )
				$handler = __NAMESPACE__ . '\\SecurityHandlers\\' . $handlerClass;

			if ( !class_exists($handler) )
				throw new Exceptions\InvalidSecurityHandlerException("Security handler '$handlerClass' does not exist.");

			// parameters as array of values is transformed in an argument list thanks to the splat operator '...'
			$ret[] = new $handler(...$hparams);
		}
		
		
		// return security handlers objects
		return $this->_securityHandlers = $ret;
    }
    
    
	
    /** 
     * Execute a command
     *
     * @param Command $d Command class to run
     * @param Request $req Request paramaters to pass to command
     * @param Application $app Application object
     * @return ReturnedValues\Value Returned value sent by command
     * @throws Exceptions\InvalidRequestException Thrown if request is not valid
     * @throws Exceptions\UnauthorizedCommandException Thrown if request is not authorized
     */
    protected function runCommand(Command $cmd, Request $req, Application $app)
    {
		$this->_authenticationRequired = false;
		$this->_authenticationPassed = false;

		
		// always create the array of security handlers as defined in app config ; it will be used or not depending on the requiresAuthentication flag of command
		$this->getSecurityHandlers($app);
				

		// checking security handlers if the command is flagged as authenticated
		if ( $cmd->requiresAuthentication() )
		{
			// set auth required flag
			$this->_authenticationRequired = true;
			
			// checking security handlers list ; returns TRUE(ok) or FALSE(ko)
			if ( !$this->checkSecurityHandlers($req) )
				throw new Exceptions\UnauthorizedCommandException('Request is not authorized.');

			// if we arrive here, required authentication passed
			$this->_authenticationPassed = true;
		}


		// validate request for command
		if ( !$cmd->validateRequest($req) )
			throw new Exceptions\InvalidRequestException("Request for command '" . $cmd->getCommandName() . "' is not valid.");


		// execute command and return result
		return $cmd->execute($req, $app);
    }
    
	
    
    /**
     * Forward a command to another one : we execute a different command with the same request 
     *
     * @param Command $d Command class to run
     * @param Request $req Request paramaters to pass to command
     * @param Application $app Application object
     * @return ReturnedValues\Value Returned value sent by command
     */
    public function forward(Command $cmd, Request $req, Application $app)
    {
        return $this->runCommand($cmd, $req, $app);
    }
        
    
	
    /** 
     * Handle command failure by user
     *
     * @param Exceptions\CommandFailedException $e
     * @return ReturnedValues\Value Returns a value representing the error, with an unsuccessful state
     */
    abstract protected function handleCommandFailure(Exceptions\CommandFailedException $e);
  
	

    /** 
     * Handle command unauthorized exception
     *
     * @return ReturnedValues\Value Returns a value representing the unauthorized command error, with an unsuccessful state
     */
    abstract protected function handleUnauthorizedCommand();
  
	

	/** 
	 * Output a value
	 *
	 * @param ReturnedValues\Value $value
	 */
	abstract protected function _outputValue(ReturnedValues\Value $value);
	
	
	
	/** 
	 * Checking security handlers
	 *
	 * @param Request $req Request object
     * @return bool Returns true if checks are passed
	 */
	public function checkSecurityHandlers(Request $req)
	{
		return array_reduce($this->_securityHandlers, function($carry, $handler) use ($req){
				return $carry && $handler->check($req);
			}, true);
	}
	
	
	
	/** 
	 * Magic method to access security handlers
	 *
	 * @param string $method
	 * @param string[] $args
	 * @return mixed
	 * @throws Exceptions\InvalidSecurityHandlerException Thrown if security handler $classname does not exist in app
	 * @throws Exceptions\InvalidParameterException Thrown if magic call to method is not supported (does not reference a security handler)
	 */
	public function __call($method, $args)
	{
	    $regs = [];
		// check we are looking for a security handler
		if ( preg_match('/^get([a-zA-Z0-9]*)SecurityHandler$/', $method, $regs) )
		{
			$class = rtrim(__NAMESPACE__, '\\') . "\\SecurityHandlers\\{$regs[1]}SecurityHandler";
			return $this->getSecurityHandler($class);
		}
		else
			throw new Exceptions\InvalidParameterException("Unsupported magic call to method '$method' in '{" . __CLASS__ . "'");
	}
	
	
	
	/** 
	 * Get a security handler
	 *
	 * @param string $classname
	 * @return SecurityHandlers\SecurityHandler
	 * @throws Exceptions\InvalidSecurityHandlerException Thrown if security handler $classname does not exist in app
	 * @throws Exceptions\ApplicationException Thrown if security handlers list has not been initialized yet
	 */
	public function getSecurityHandler($classname)
	{
		if ( is_null($this->_securityHandlers) )
			// if nothing found, this is an error
			throw new Exceptions\ApplicationException("Security handlers layer has not been initialized.");
		

		foreach ( $this->_securityHandlers as $sech )
			if ( $sech instanceof $classname )
				return $sech;
		
		// if nothing found, this is an error
		throw new Exceptions\InvalidSecurityHandlerException("Security handler '$classname' not found in application security layer.");
	}
  
	

    /** 
     * Execute a command and handle returned value accordingly
     *
     * @param Application $app Application object
     * @return ReturnedValues\Value Returned value sent by command
     */
    public function run(Application $app)
    {
        try
        {
            // get request and command objects
            $req = $this->getRequest();
            $cmd = $this->getCommand($req, $app);

			
			
            try
            {
				// check security if required, execute command and get its returned value
				// intercept command failed by user exception (call to fail method)
				// intercept command unauthorized so that the user can be redirect to login page ; otherwise 
				//   the exception propagates to top-level catch block and is handled by simpleExceptionHandler, 
				//   which is not suitable for general public (top-level catch block should only be used to catch
				//   exceptions in unpredictable cases)
                $ret = $this->runCommand($cmd, $req, $app);
            }
            catch(Exceptions\UnauthorizedCommandException $e)
			{
				// if unauthorized command
                $ret = $this->handleUnauthorizedCommand();
			}
            catch(Exceptions\CommandFailedException $e)
            {
                // if command aborted by user, get a returned value with unsuccessful state and an appropriate error message
                $ret = $this->handleCommandFailure($e);
            }
            

            // check that we have a returned value, which is an object
            if ( !$ret || !is_object($ret) || !($ret instanceof \Nettools\Simple_Framework\ReturnedValues\Value) )
                throw new Exceptions\UnknownReturnException("Return value for command '" . $cmd->getCommandName() . "' is unknown or not a ReturnedValues\\Value class.");

			
			// output value
			$this->_outputValue($ret);			
			return $ret;
        }
        catch(\Throwable $e)
        {
            if ( $app->registry->exists('appcfg') && $app->registry->appcfg->application && ($eh = $app->registry->appcfg->application->exceptionHandler) && class_exists($eh) )
                $eh;
            else
                $eh = \Nettools\Core\ExceptionHandlers\SimpleExceptionHandler::class;
            
            $h = new $eh();
            $h->handleException($e);
        }

    }
    
}



?>