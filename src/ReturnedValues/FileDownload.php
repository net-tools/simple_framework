<?php
/**
 * FileUpload
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\ReturnedValues;




/**
 * Class for a file download returned by a command
*/
class FileDownload extends Download {
    
    public function output()
    {
        if ( !file_exists($this->_value) )
            throw \Nettools\Simple_Framework\Exceptions\InvalidParameterException("File '$this->_value' not found.");
            
        readfile($this->_value);
    }
}



?>