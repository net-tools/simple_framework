<?php

namespace Nettools\Simple_Framework;



/**
 * Class for app request (GET or POST)
*/
class Request {
    
    /**
     * @var string[] Request parameters as associative array
     */
    protected $_params = NULL;
    
    /** 
     * @var FileUploadRequest[] Associative array of FileUploadRequest objects describing file uploads
     */
    protected $_fileUploads = NULL;
    
    
    /** 
     * Constructor for Request
     *
     * @param string[] $params Request parameters as associative array
     * @param FileUploadRequest[] $fileUploads Associative array of FileUploadRequest objects describing file uploads
     */
    public function __construct($params, $fileUploads = array())
    {
        $this->_params = $params;
        $this->_fileUploads = $fileUploads;
    }
    
    
    /** 
     * Test if a parameter exists (eventually with empty string value)
     * 
     * @param string $k Parameter name
     * @return bool Returns true if parameter $k exists
     */
    public function test($k)
    {
        return !is_null($this->_params[$k]);
    }
    
    
    /** 
     * Test if an array of parameters exist (eventually with empty string values)
     * 
     * @param string[] $keys Array of parameter names
     * @return bool Returns true if all parameters exists, false otherwise
     * @throws Exceptions\InvalidParameterException Thrown if parameter $keys is not an array
     */
    public function testArray($keys)
    {
        if ( !is_array($keys) )
            throw new Exceptions\InvalidParameterException("'keys' parameter is not an array.");
        
        foreach ( $keys as $k )
            if ( !$this->test($k) )
                return false;
        
        return true;
    }
    
    
	/**
     * Test if a file upload has been sent with request. No check is performed on successfull upload
     *
     * @param string $k Parameter name
     * @return bool Returns true if a file upload parameter named $k has been sent
     */
	function testFileUpload($k)
	{
		return !is_null($this->_fileUploads[$k]);
	}
    
    
	/**
     * Test if some file uploads have been sent with request. No check is performed on successfull upload
     *
     * @param string[] $keys Parameters name
     * @return bool Returns true if all file uploads in $keys parameter have been sent with request
     * @throws Exceptions\InvalidParameterException Thrown if parameter $keys is not an array
     */
	function testFileUploadArray($keys)
	{
        if ( !is_array($keys) )
            throw new Exceptions\InvalidParameterException("'keys' parameter is not an array.");
        
        foreach ( $keys as $k )
            if ( !$this->testFileUpload($k) )
                return false;

        return true;
	}

    
    /** 
     * Get a parameter value
     * 
     * @param string $k Parameter name
     * @return string Parameter value
     */
    public function get($k)
    {
        return $this->_params[$k];
    }
    
    
    /** 
     * Get a file upload value
     * 
     * @param string $k Parameter name
     * @return FileUploadRequest FileUploadRequest object
     */
    public function getFileUpload($k)
    {
        return $this->_fileUploads[$k];
    }
    
    
    /** 
     * Magic accessor
     * 
     * @param string $k Parameter name
     * @return string Parameter value
     */
    public function __get($k)
    {
        return $this->get($k);
    }
}



?>