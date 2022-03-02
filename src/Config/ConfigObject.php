<?php
/**
 * ConfigObject
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\Config;



/**
 * Class for config
 */
class ConfigObject extends \Nettools\Simple_Framework\Config\Config{
    
    /** 
     * @var \Stdclass Config litteral object
     */
    protected $_configObject = NULL;
    
    
    
    /**
     * Constructor
     *
     * @param \Stdclass $obj Contains a litteral object with config data
     */
    public function __construct(\Stdclass $obj, $readonly = true)
    {
        // a ConfigObject is always read-only
        parent::__construct($readonly);
        $this->_configObject = $obj;
    }
    
    
    public function get($k)
    {
        return property_exists($this->_configObject, $k) ? $this->_configObject->$k : null;
    }

    
    public function doSet($k, $v)
    {
        $this->_configObject->$k = $v;
    }

    
    public function asJson()
    {
        return json_encode($this->_configObject, JSON_PRETTY_PRINT);
    }
}



?>