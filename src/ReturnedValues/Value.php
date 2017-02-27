<?php
/**
 * Value
 *
 * @author Pierre - dev@net-tools.ovh
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
     * Do the output of the value on stdout
     */
    abstract function output();
    
    
    /**
     * Do the headers output on stdout, if required ; for example, Download return value send headers with Mimetype and suggested filename
     */
    public function headers()
    {
        
    }
    
    
    /**
     * Terminate the output of the value on stdout ; for example, Download or Json return value halt the script
     */
    function terminateOutput()
    {        
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