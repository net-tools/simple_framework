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
    
    public function doOutput()
    {
        echo $this->_value;
    }
}



?>