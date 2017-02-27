<?php
/**
 * Download
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\ReturnedValues;





/**
 * Class for a Download value returned by a command
*/
abstract class Download extends Value {
    
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
     * Output headers for a download
     */
    public function headers()
    {
        header("Content-Type: " . $this->_contentType . "; name=\"" . $this->_filename . "\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Disposition: attachment; filename=\"" . $this->_filename . "\"");
        header("Expires: 0");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
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