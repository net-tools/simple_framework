<?php
/**
 * PrivateConfig
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\Config;



/**
 * Class for private config, hide underlying object config so that sensitive data doesn't appear in print_r statements
 */
class PrivateConfig extends \Nettools\Simple_Framework\Config\Config{
    
    /** 
     * @var \Nettools\Simple_Framework\Config\Config Underlying config object 
     */
    protected $_private_config = NULL;
    
    
    
    /**
     * Constructor
     *
     * @param \Nettools\Simple_Framework\Config\Config Underlying config object
     */
    public function __construct(\Nettools\Simple_Framework\Config\Config $cfg, $readonly = true)
    {
        parent::__construct($readonly);
        $this->_private_config = $cfg;
    }
	
	
	/** 
	 * Magic method to forbid access to private config
	 */
	public function __debugInfo()
	{
		return ['_private_config' => '** HIDDEN **'];
	}
    
    
    public function get($k)
    {
        return $this->_private_config->$k;
    }

    
    public function doSet($k, $v)
    {
        $this->_private_config->$k = $v;
    }

    
    public function asJson()
    {
        return json_encode($this->_private_config, JSON_PRETTY_PRINT);
    }
}



?>