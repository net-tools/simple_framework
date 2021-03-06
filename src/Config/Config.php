<?php
/**
 * Config
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */



namespace Nettools\Simple_Framework\Config;



/**
 * Abstract base class for config
 */
abstract class Config {
    
    // readonly
    protected $_readonly = NULL;
    
    
    /**
     * Constructor
     *
     * @param bool $readonly True if this Config object is read-only, false otherwise
     */
    public function __construct($readonly = true)
    {
        $this->_readonly = $readonly;
    }
    
    
    /**
     * read-only accessor
     *
     * @return bool True if registry is read-only
     */
    public function isReadOnly()
    {
        return $this->_readonly;
    }
    
    
    /** 
     * Config value GET accessor
     * 
     * @param string $k Config value name
     * @return mixed Return config value
     */
    abstract public function get($k);

    
    /** 
     * Config value SET accessor
     * 
     * @param string $k Config value name
     * @param mixed $v Config value 
     */
    public function set($k, $v)
    {
        if ( $this->isReadOnly() )
            throw new \Nettools\Simple_Framework\Exceptions\NotAuthorizedException("Registry is read-only.");
            
        $this->doSet($k, $v);
        
        $this->commit();
    }

    
    /** 
     * Config value SET updater to implement in child classes
     * 
     * @param string $k Config value name
     * @param mixed $v Config value 
     */
    abstract protected function doSet($k, $v);

    
    /** 
     * Commit read/write registry to storage (only if not readonly)
     */
    public function commit()
    {
        if ( !$this->isReadOnly() )
            $this->doCommit();
    }

    
    /** 
     * Method to commit read/write registry to storage
     */
    protected function doCommit()
    {
    }

    
    /** 
     * Magic method for property getter access
     * 
     * @param string $k Property name
     * @return mixed Return property value
     */
    public function __get($k)
    {
        return $this->get($k);
    }

    
    /** 
     * Magic method for property setter access
     * 
     * @param string $k Property name
     * @param mixed $v Property value
     */
    public function __set($k, $v)
    {
        return $this->set($k, $v);
    }
    
    
    /** 
     * Get the config data as JSON 
     * 
     * @return string Json-string of config data
     */
    abstract public function asJson();    
}



?>