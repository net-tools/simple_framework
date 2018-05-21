<?php
/**
 * StringDownload
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\ReturnedValues;




/**
 * Class for a data download returned by a command
*/
class StringDownload extends Download {
    
    /**
     * Magic method when casting to string
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->_value;
    }
}



?>