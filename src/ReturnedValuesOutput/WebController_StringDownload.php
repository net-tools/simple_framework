<?php
/**
 * WebController_StringDownload
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */



namespace Nettools\Simple_Framework\ReturnedValuesOutput;





/**
 * Class for a Download string value output
 */
class WebController_StringDownload extends WebController_Download {
    
	static function output(\Nettools\Simple_Framework\ReturnedValues\Download $value)
	{
		parent::output($value);
        echo $value;
		die();
	}
}



?>