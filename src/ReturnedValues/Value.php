<?php
/**
 * Value
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\ReturnedValues;



/**
 * Base class for a value returned by a command
 */
abstract class Value {
    
    /**
     * @var bool Is value successful ?
     */
    protected $_successful = NULL;
	
	
    
    
    /**
     * Constructor
     * 
     * @param bool $successful Is value successful ? By default, yes
     */
    public function __construct($successful = true)
    {
        $this->_successful = $successful;
    }
    
	
    
    /**
     * Is this value successful ? By default, yes
     *
     * @return bool Returns true, meaning this value does not represent an error condition
     */
    public function isSuccessful()
    {
        return $this->_successful;
    }

}



?>