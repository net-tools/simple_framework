<?php

namespace Nettools\Simple_Framework\ReturnedValues;





/**
 * Class for a Download value returned by a command
*/
class Download extends Value {
    
    // value or file to be outputed to the browser for user download
    protected $_value = NULL;
    protected $_contentType = NULL;
    protected $_filename = NULL;
    
    
    /** 
     * Constructor of Download value
     * 
     * @param string $value Download filename or string value to be downloaded
     */
    public function __construct($value, $filename, $contentType = 'application/octet-stream')
    {
        parent::__construct(true);
        $this->_value = $value;
        $this->_filename = $filename;
        $this->_contentType = $contentType;
    }
    
    
    /** 
     * Method to output the value ; to implement in child classes ; data should be outputed directly, not as a return function value
     */
    abstract function doOutput();
    
    
    /**
     * Do the output of the Download value, and halts the script
     */
    public function output()
    {
        $this->addHeader("Content-Type: " . $this->_contentType . "; name=\"" . $this->_filename . "\"");
        $this->addHeader("Content-Transfer-Encoding: binary");
        $this->addHeader("Content-Disposition: attachment; filename=\"" . $this->_filename . "\"");
        $this->addHeader("Expires: 0");
        $this->addHeader("Cache-Control: no-cache, must-revalidate");
        $this->addHeader("Pragma: no-cache");
        
        $this->doOutput();
        die();
    }
}



?>