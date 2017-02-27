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
     * @throws \Nettools\Simple_Framework\Exceptions\InvalidParameterException Thrown if $value is not a json-formatted string
     */
    public function __construct($value, $successful = true)
    {
        parent::__construct($successful);
        
        // check value is in json format
        if ( is_null(json_decode($value)) )
            throw new \Nettools\Simple_Framework\Exceptions\InvalidParameterException('Json returned value is not properly formatted.');
        
        $this->_value = $value;
    }
    
    
    public function __toString()
    {
        return $this->_value;
    }

    
    /**
     * Send headers for xmlhttp response in json
     */
    public function headers()
    {
        NetworkingHelper::sendXmlHttpResponseHeaders();
    }
    
    
    /**
     * Do the output of the Json value
     */
    public function output()
    {
        echo $this->_value;
    }
    
    
    /**
     * Terminate the output of the value on stdout ; for example, Download or Json return value halt the script
     */
    function terminateOutput()
    {     
        die();
    }
    
}



?>