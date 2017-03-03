<?php
/**
 * JsonFile
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */



namespace Nettools\Simple_Framework\Config;



/**
 * Class for app config from JSON file
 */
class JsonFile extends Json {
     
    protected $_path = NULL;
    

    
    /**
     * Constructor
     *
     * @param string $path Path to file containing JSON config data
     * @param bool $readonly True if this Config object is read-only, false otherwise
     */
    public function __construct($path, $readonly = true)
    {
        parent::__construct(file_exists($path)?file_get_contents($path):'{}', $readonly);
        
        $this->_path = $path;
    }
    
    
    protected function doCommit()
    {
        $f = fopen($this->_path, 'w');
        fwrite($f, $this->asJson());
        fclose($f);
    }
}



?>