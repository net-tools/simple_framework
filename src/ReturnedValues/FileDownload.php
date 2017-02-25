<?php

namespace Nettools\Simple_Framework\ReturnedValues;




/**
 * Class for a file download returned by a command
*/
class FileDownload extends Download {
    
    public function doOutput()
    {
        readfile($this->_value);
    }
}



?>