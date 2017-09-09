<?php
/**
 * Registry
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework;



/**
 * Class for centralizing config data for application (root config, user config, etc.)
 */
class Registry {

    /**
     * @var Config\Config[] Associative array of Config\Config objects
     */
    protected $_registries = array();
    
        
    /** 
     * Magic method for registry access
     * 
     * @param string $k Registry name to access
     * @return Config\Config Config object
     * @throws Exceptions\InvalidParameterException Thrown if registry named $k does not exist
     */
    public function __get($k)
    {
        if ( !$this->exists($k) )
            throw new Exceptions\InvalidParameterException("Registry '$k' does not exist.");
        
        return $this->_registries[$k];
    }
    
    
    /**
     * Constructor
     *
     * @param Config\Config[] $registries Array of Config\Config objects
     * @throws Exceptions\InvalidParameterException Thrown if one of the registries doesn't inherit from Config\Config
     */
    public function __construct($registries = array())
    {
        // check all registries inherit from Config\Config
        foreach ( $registries as $k=>$reg )
            if ( !($reg instanceof \Nettools\Simple_Framework\Config\Config) )
                throw new Exceptions\InvalidParameterException("Registry '$k' does not inherit from Config\\Config.");
                
        $this->_registries = $registries;
    }
    
    
    /**
     * Test registry existence
     * 
     * @param string $k Registry name
     * @return bool 
     */
    public function exists($k)
    {
        return array_key_exists($k, $this->_registries);
    }
}



?>