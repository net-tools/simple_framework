<?php
/**
 * Command
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework;



use \Nettools\Core\Helpers\FileHelper;
    



/**
 * Base class for application command
 */
abstract class Command {
    
    /**
     * @var mixed Returned value (which is a Value object)
     */
    protected $_return;
    
    
    
    /** 
     * Get a string describing the command. Usually the CMD get/post parameter
     *
     * @return string The command name being executed, in lowercase
     */
    public function getCommandName()
    {
        // get the classname with namespace, remove it and only return classname
        return strtolower(substr(strrchr('\\'.get_class($this), '\\'), 1));
    }
    
    
    /** 
     * Get return value
     * 
     * @return ReturnedValues\Value Value returned by command execution
     */
    public function getReturn()
    {
        return $this->_return;        
    }
    
    
    /** 
     * Validate a request (mandatory parameters)
     * 
     * @param Request $r Request to validate
     * @return bool Returns true if request is valid
     */
    public function validateRequest(Request $r)
    {
        return true;
    }
    
    
    /** 
     * Execute request on some parameters
     *
     * @param Request $req Object request
     * @param Application $app Application object
     * @return ReturnedValues\Value Return a Value object representing the command result
     * @throws Exceptions\CommandFailedException Thrown if user aborts the command execution (due to some error condition, for example)
     */
    abstract public function execute(Request $req, Application $app);
    
    
    /**
     * Aborts the command execution
     * 
     * @param string $msg Error message
     * @throws Exceptions\CommandFailedException 
     */
    protected function fail($msg = NULL)
    {
        throw new Exceptions\CommandFailedException($msg ? $msg : 'Command \'' . $this->getCommandName() . '\' aborted');
    }
    
    
    /** 
     * Returns a NULL value (shortcut to `returnPHP(NULL)`)
     */
    protected function returnNull()
    {
        return $this->returnPHP(NULL);
    }
    
    
    /** 
     * Returns a PHP value
     *
     * @param mixed $v Value to be returned (any kind of PHP type)
     */
    protected function returnPHP($v)
    {
        return $this->_return = new ReturnedValues\PHP($v);
    }
    
    
    /** 
     * Returns HTML content
     *
     * @param string $html HTML string to be returned 
     */
    protected function returnHTML($html)
    {
        return $this->_return = new ReturnedValues\HTML($html);
    }
    
    
    /** 
     * Returns a PHP string (useful for output to screen)
     *
     * @param string $s 
     */
    protected function returnString($s)
    {
        return $this->returnPHP($s);
    }
    
    
    /** 
     * Returns a PHP float 
     *
     * @param float $f
     */
    protected function returnFloat($f)
    {
        return $this->returnPHP($f);
    }
    
    
    /** 
     * Returns a PHP int 
     *
     * @param int $i
     */
    protected function returnInt($i)
    {
        return $this->returnPHP($i);
    }
    
    
    /** 
     * Returns a PHP bool
     *
     * @param bool $b
     */
    protected function returnBool($b)
    {
        return $this->returnPHP($b);
    }
    
    
    /** 
     * Returns a Json string (useful for xmlhttp requests)
     *
     * @param string|string[]|object $s Value to be returned (either a json-formatted string, an associative array or a litteral object)
     */
    protected function returnJson($s)
    {
        if ( is_string($s) )
            return $this->_return = new ReturnedValues\Json($s);
        else
            return $this->_return = new ReturnedValues\Json(json_encode($s));
    }
    
    
    /** 
     * Prepare a string download
     *
     * @param string $s Value to be downloaded
     * @param string $fname Filename to suggest to user
     * @param string $contentType Content-type to output
     */
    protected function returnStringDownload($s, $fname, $contentType = 'application/octet-stream')
    {
        return $this->_return = new ReturnedValues\StringDownload($s, $fname, $contentType);
    }
    
    
    /** 
     * Prepare a file download
     *
     * @param string $s File path to the file to be downloaded
     * @param string $fname Filename to suggest to user
     * @param string $contentType Content-type to output
     */
    protected function returnFileDownload($s, $fname = NULL, $contentType = NULL)
    {
        if ( !$fname )
            $fname = basename($s);
        if ( !$contentType )
            $contentType = FileHelper::guessMimeType($s);
            
        return $this->_return = new ReturnedValues\FileDownload($s, $fname, $contentType);
    }
}



?>