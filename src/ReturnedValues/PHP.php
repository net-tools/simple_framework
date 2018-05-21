<?php
/**
 * PHP
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\ReturnedValues;



/**
 * Class for a PHP value returned by a command
 */
class PHP extends Value {
    
    // value to be returned
    protected $_value = NULL;
    
    
    /** 
     * Constructor of PHP value
     * 
     * @param mixed $value Value to be returned (any kind of PHP type)
     * @param bool $successful Is value successful ? By default, yes
     */
    public function __construct($value, $successful = true)
    {
        parent::__construct($successful);
        $this->_value = $value;
    }
    
   
    /**
     * Get value (usually, some HTML content or a computed value to use in page template)
     * 
     * @return mixed Value returned by command
     */
    public function getValue()
    {
        return $this->_value;
    }
    
	
    
    /**
     * Magic method when casting to string
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->_value ? (string)($this->_value) : '';
    }
}



?>