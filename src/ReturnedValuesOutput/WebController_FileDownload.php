<?php
/**
 * WebController_FileDownload
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */



namespace Nettools\Simple_Framework\ReturnedValuesOutput;





/**
 * Class for a Download file value output
 */
class WebController_FileDownload extends WebController_Download {
    
	static function output(\Nettools\Simple_Framework\ReturnedValues\Download $value)
	{
		parent::output($value);
		
        if ( !file_exists($value->getValue()) )
            throw \Nettools\Simple_Framework\Exceptions\InvalidParameterException("File '" . $value->getValue() . "' not found.");
		
        readfile($value->getValue());
		die();
	}
}



?>