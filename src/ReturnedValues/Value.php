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
     * Do the output of the value on stdout as soon as possible.
     *
     * To be used for responding with json or data download and then halting the script.
     * HTML content should be displayed by user with `echo $value` at the right place in the page
     * template, which will call the magic `__toString()` method to convert the returned value to a string ; this
     * magic method should be defined in classes inheriting from Value, or another way of fetching the value must be
     * implemented. For example, inheriting class PHP has a `getValue()` method.
     */
    abstract function immediateOutput();
    
    
    /**
     * Do the headers output on stdout, if required ; for example, Download return value send headers with Mimetype and suggested filename
     */
    public function headers()
    {
    }
    
    
    /**
     * Terminate the output of the value on stdout ; for example, Download or Json return values halt the script
     */
    function terminateImmediateOutput()
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