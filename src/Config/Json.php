<?php

namespace Nettools\Simple_Framework\Config;



/**
 * Abstract base class for app config
 */
class Json extends Config {
     
    protected $_json = NULL;
    protected $_path = NULL;
    

    
    public function asJson()
    {
        return json_encode($this->_json, JSON_PRETTY_PRINT);
    }
    
    
    /**
     * Constructor
     *
     * @param string $path Path to file containing JSON config data
     * @param bool $readonly True if this Config object is read-only, false otherwise
     */
    public function __construct($path, $readonly = true)
    {
        parent::__construct($readonly);
        
        $this->_path = $path;
        
        if ( !file_exists($path) )
            $this->_json = (object)array();
        else
            $this->_json = json_decode(file_get_contents($path));
        
        if ( is_null($this->_json) )
            $this->_json = (object)array();
    }
    
    
    public function get($k)
    {
        return $this->_json->{$k};
    }

    
    public function commit()
    {
        $f = fopen($this->_path, 'w');
        fwrite($f, $this->asJson());
        fclose($f);
    }
    

    public function set($k, $v)
    {
        if ( $this->_readonly )
            throw new \Nettools\Simple_Framework\Exceptions\NotAuthorizedException("Registry is read-only.");
            
        $this->_json->{$k} = $v;
        $this->commit();
    }
    
}



?>