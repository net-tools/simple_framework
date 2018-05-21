<?php
/**
 * Application
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework;



/**
 * Class for application handling
 *
 * In app registry, the following parameters are defined :
 *
 * - application.exceptionHandler : classname for exception handler
 *
 */
class Application {

    /** 
     * @var Controller Application controller object
     */
    protected $_controller = NULL;
    
    
    /**
     * @var Registry Application registry 
     */
    protected $_registry = NULL;
    
    
    
    /**
     * Magic method for access to controller or registry protected properties
     *
     * @param string $k Property name
     * @return mixed Returns the property named $k
     * @throws Exceptions\InvalidParameterException Thrown if property $k is not a member of Application
     */
    public function __get($k)
    {
        if ( !property_exists(get_class($this), "_$k") )
            throw new Exceptions\InvalidParemeterException("Property '$k' does not exist in application class.");
        
        return $this->{"_$k"};
    }
    
    
    /** 
     * Launch application
     *
     * @return ReturnedValues\Value Returned value sent by command
     */
    public function run()
    {
        return $this->_controller->run($this);
    }
    
        
    /** 
     * Constructor
     *
     * @param Controller $controller Controller object for application
     * @param Registry $registry Registry object for application
     */
    public function __construct(Controller $controller, Registry $registry)
    {
        $this->_controller = $controller;
        $this->_registry = $registry;
    }
    
}



?>