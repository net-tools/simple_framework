<?php
/**
 * Php
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\ReturnedValues;



/**
 * Class for HTML content returned by a command
 *
 * Used for generating page content
 */
class HTML extends PHP {
    

    /** 
     * Constructor of HTML value
     * 
     * @param mixed $value HTML content to return
     * @param bool $successful Is value successful ? By default, yes
     */
    public function __construct($value, $successful = true)
    {
        if ( !is_string($value) )
            throw new \Nettools\Simple_Framework\Exceptions\InvalidParameterException("Parameter 'value' of HTML object is not a string");
        
        parent::__construct($value, $successful);
    }
    
    
    /**
     * Append HTML content to value
     *
     * @param string $html 
     * @return HTML Return $this for chaining calls
     */
    public function append($html)
    {
        $this->_value .= $html;
        return $this;
    }
    
    
    /** 
     * Perform replacements in value
     *
     * @param string $pattern Regular expression pattern 
     * @param string $replacement String to use as replacement
     * @return HTML Return $this for chaining calls
     */
    public function preg_replace($pattern, $replacement)
    {
        preg_replace($pattern, $replacement, $this->_value);
        return $this;
    }
    
    
}



?>