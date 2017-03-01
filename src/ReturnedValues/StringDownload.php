<?php
/**
 * StringDownload
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\ReturnedValues;




/**
 * Class for a data download returned by a command
*/
class StringDownload extends Download {
    
    public function immediateOutput()
    {
        echo $this->_value;
    }
    
    
    /**
     * Magic method when casting to string
     * 
     * Returns content to the downloaded
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_value;
    }
}



?>