<?php
/**
 * Json
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\ReturnedValues;



use \Nettools\Core\Helpers\NetworkingHelper;



/**
 * Class for a Json value returned by a command.
 *
 * The output method will send the $_value property to stdout and halt the script.
 */
class Json extends Value {
    
    // value to be returned
    protected $_value = NULL;
    
    
    /** 
     * Constructor of Json value
     * 
     * @param string $value Json value to be returned (string format)
     * @param bool $successful Is value successful ? By default, yes
     */
    public function __construct($value, $successful = true)
    {
        parent::__construct($successful);
        $this->_value = $value;
    }
    
    
    /**
     * Do the output of the Json value, and halts the script
     */
    public function output()
    {
        NetworkingHelper::sendXmlHttpResponseHeaders();
        
        die($this->_value);
    }
}



?>