<?php

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
    
    
    public function output()
    {
        // nothing to be done here
    }
    
    
    public function __toString()
    {
        return $this->_value ? $this->_value : '';
    }
}



?>