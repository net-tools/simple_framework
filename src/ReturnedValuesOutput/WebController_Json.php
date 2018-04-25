<?php
/**
 * WebController_Json
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */



namespace Nettools\Simple_Framework\ReturnedValuesOutput;


use \Nettools\Core\Helpers\NetworkingHelper;




/**
 * Class for a Json value output
 *
 * The output method will send the $_value property to stdout and halt the script.
 */
class WebController_Json {
    
	static function output(\Nettools\Simple_Framework\ReturnedValues\Json $value)
	{
        NetworkingHelper::sendXmlHttpResponseHeaders();
		echo $value;
		die();
	}
}



?>