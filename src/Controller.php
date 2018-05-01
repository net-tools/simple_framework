<?php
/**
 * Controller
 *
 * @author Pierre - dev@net-tools.ovh
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
     * Create the Request object
     *
     * @return Request Returns a Request object for command parameters
     */
    abstract function getRequest();
    
    
	
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
		return $ret;
    }
    
    
	
    /** 
     * Execute a command
     *
     * @param Command $d Command class to run
     * @param Request $req Request paramaters to pass to command
     * @param Application $app Application object
     * @return ReturnedValues\Value Returned value sent by command
     * @throws Exceptions\InvalidRequestException Thrown if request is not valid
     */
    protected function runCommand(Command $cmd, Request $req, Application $app)
    {
        // validate request for command
        if ( !$cmd->validateRequest($req) )
            throw new Exceptions\InvalidRequestException("Request for command '" . $cmd->getCommandName() . "' is not valid.");
        
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
     * @param Exceptions\ApplicationException $e
     * @return ReturnedValues\Value Returns a value representing the error, with an unsuccessful state
     */
    abstract protected function handleCommandFailure(Exceptions\ApplicationException $e);
  
	

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
     * @param Application $app Application object
     * @throws Exceptions\UnauthorizedCommand $e
	 */
	protected function checkSecurityHandlers(Request $req, Application $app)
	{
		if ( !array_reduce($this->getSecurityHandlers($app), function($carry, $handler) use ($req){
				return $carry && $handler->check($req);
			}, true) )
			throw new Exceptions\UnauthorizedCommandException('Request is not authorized.');		
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
				// checking security handlers if the command is flagged as authenticated
				if ( $cmd->requiresAuthentication() )
					// if auth ok, nothing happens ; if auth ko, an UnauthorizedCommandException exception is thrown
					$this->checkSecurityHandlers($req, $app);

				
				// execute command and get its returned value ; intercept command failed (by user) exception
                $ret = $this->runCommand($cmd, $req, $app);
            }
            catch(Exceptions\UnauthorizedCommandException $e)
			{
				// if unauthorized command
                $ret = $this->handleCommandFailure($e);
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