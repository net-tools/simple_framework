<?php
/**
 * Object
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\Config;



/**
 * Class for config
 */
class Object extends Config{
    
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
        // an Object config is always read-only
        parent::__construct($readonly);
        $this->_configObject = $obj;
    }
    
    
    public function get($k)
    {
        return $this->_configObject->$k;
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