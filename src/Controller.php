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
     * @var Application Application object
     */
    protected $_app = NULL;
    
    

    /** 
     * Create the Request object
     *
     * @return Request Returns a Request object for command parameters
     */
    abstract protected function getRequest();
    
    
    /** 
     * Constructor of controller
     *
     * @param Application $app Application object
     * @param string $ns Namespace used in the user application for commands
     */
    public function __construct(Application $app, $ns)
    {
        $this->_app = $app;
        $this->_commandsNamespace = $ns;
    }
    
    
    /**
     * Get a command object, whose class is built from the CMD reserved parameter
     *
     * @param Request $req
     * @return Command Returns a command object for this request
     * @throws Exceptions\InvalidCommandException Thrown if command class cannot be found
     */
    protected function getCommand(Request $req)
    {
        $cmd = $req->cmd;
        $ns = $this->_commandsNamespace;
        
        
        // if command not in request (CMD parameter not set)
        if ( !$cmd )
            // if no app config directive for default command
            if ( !$this->_app->registry->exists('appcfg') || !$this->_app->registry->appcfg->controller || !($cmd = $this->_app->registry->appcfg->controller->userDefaultCommand) )
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
     * Execute a command
     *
     * @param Command $d Command class to run
     * @param Request $req Request paramaters to pass to command
     * @return ReturnedValues\Value Returned value sent by command
     * @throws Exceptions\InvalidRequestException Thrown if request is not valid
     */
    public function runCommand(Command $cmd, Request $req)
    {
        // validate request for command
        if ( !$cmd->validateRequest($req) )
            throw new Exceptions\InvalidRequestException("Request for command '" . $cmd->getCommandName() . "' is not valid.");
        
        return $cmd->execute($req, $this->_app);
    }
    
    
    /**
     * Forward a command to another one : we execute a different command with the same request 
     *
     * @param Command $d Command class to run
     * @param Request $req Request paramaters to pass to command
     * @return ReturnedValues\Value Returned value sent by command
     */
    public function forward(Command $cmd, Request $req)
    {
        return $this->runCommand($cmd, $req);
    }
        
    
    /** 
     * Handle command failure by user
     *
     * @param Exceptions\CommandFailedException $e
     * @return ReturnedValues\Value Returns a value representing the error, with an unsuccessful state
     */
    abstract protected function handleCommandFailure(Exceptions\CommandFailedException $e);
    

    /** 
     * Execute a command and handle returned value accordingly
     *
     * @return ReturnedValues\Value Returned value sent by command
     */
    public function run()
    {
        try
        {
            // get request and command objects
            $req = $this->getRequest();
            $cmd = $this->getCommand($req);
            
            try
            {
                // execute command and get its returned value ; intercept command failed (by user) exception
                $ret = $this->runCommand($cmd, $req);
            }
            catch(Exceptions\CommandFailedException $e)
            {
                // if command aborted by user, get a returned value with unsuccessful state and an appropriate error message
                $ret = $this->handleCommandFailure($e);
            }
            

            // check that we have a returned value, which is an object
            if ( !$ret || !is_object($ret) || !($ret instanceof \Nettools\Simple_Framework\ReturnedValues\Value) )
                throw new Exceptions\UnknownReturnException("Return value for command '" . $cmd->getCommandName() . "' is unknown or not a ReturnedValues\\Value class.");

            // do command output (only used by ReturnedValues\Json and ReturnedValues\Download)
            $ret->output();
            return $ret;
        }
        catch(\Throwable $e)
        {
            if ( $this->_app->registry->exists('appcfg') && $this->_app->registry->appcfg->application && ($eh = $this->_app->registry->appcfg->application->exceptionHandler) && class_exists($eh) )
                $eh;
            else
                $eh = '\\Nettools\\Core\\ExceptionHandlers\\SimpleExceptionHandler';
            
            $h = new $eh();
            $h->handleException($e);
        }

    }
    
    


}



?>