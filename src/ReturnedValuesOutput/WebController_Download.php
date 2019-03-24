<?php
/**
 * WebController_Download
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */



namespace Nettools\Simple_Framework\ReturnedValuesOutput;




/**
 * Class for a Download value output
 */
class WebController_Download {
    
	static function output(\Nettools\Simple_Framework\ReturnedValues\Download $value)
	{
        header("Content-Type: " . $value->getContentType() . "; name=\"" . $value->getFilename() . "\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Disposition: attachment; filename=\"" . $value->getFilename() . "\"");
        header("Expires: 0");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
	}
}



?>