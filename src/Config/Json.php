<?php
/**
 * Json
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */



namespace Nettools\Simple_Framework\Config;



/**
 * Json config from Json-formatted string
 */
class Json extends ConfigObject {

    /**
     * Constructor
     *
     * @param string $json JSON-formatted string
     * @param bool $readonly True if this Config object is read-only, false otherwise
     */
    public function __construct($json = '{}', $readonly = true)
    {
        if ( is_null($json = json_decode($json)) )
            throw new \Nettools\Simple_Framework\Exceptions\InvalidParameterException('JSON parameter is NULL or invalid JSON string.');

        parent::__construct($json, $readonly);
    }
    
}



?>