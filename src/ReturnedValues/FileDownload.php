<?php
/**
 * FileDownload
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\ReturnedValues;




/**
 * Class for a file download returned by a command
*/
class FileDownload extends Download {
    
    public function immediateOutput()
    {
        if ( !file_exists($this->_value) )
            throw \Nettools\Simple_Framework\Exceptions\InvalidParameterException("File '$this->_value' not found.");
            
        readfile($this->_value);
    }
    
    
    /**
     * Magic method when casting to string
     * 
     * Returns file content to download
     *
     * @return string File path to file to output
     */
    public function __toString()
    {
        if ( !file_exists($this->_value) )
            throw \Nettools\Simple_Framework\Exceptions\InvalidParameterException("File '$this->_value' not found.");
            
        return file_get_contents($this->_value);
    }
}



?>