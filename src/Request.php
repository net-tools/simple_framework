<?php
/**
 * Request
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */




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
	 * @var string method Request method (for web apps, GET/POST/etc.)
	 */
	protected $_method = NULL;
    
    
    /** 
     * Constructor for Request
     *
     * @param \stdClass|string[] $params Request parameters as associative array or an object litteral
     * @param FileUploadRequest[] $fileUploads Associative array of FileUploadRequest objects describing file uploads
     * @throws Exceptions\InvalidParameterException Thrown if parameter $params or $fileUploads parameters are not arrays
     */
    public function __construct($params, $fileUploads = array(), $method = NULL)
    {
        if ( !is_array($params) && !(is_object($params) && (get_class($params) == 'stdClass')) )
            throw new Exceptions\InvalidParameterException("'params' parameter is not an array or an object litteral.");
        if ( !is_array($fileUploads) )
            throw new Exceptions\InvalidParameterException("'fileUploads' parameter is not an array.");

		if ( is_array($params) )
			$this->_params = $params;
		else 
			$this->_params = (array)$params;
		
        $this->_fileUploads = $fileUploads;
		$this->_method = $method;
    }
    
    
    /** 
     * Test if a parameter exists (eventually with empty string value)
     * 
     * @param string $k Parameter name
     * @return bool Returns true if parameter $k exists
     * @throws Exceptions\InvalidParameterException Thrown if parameter $k is not a string
     */
    public function test($k)
    {
        if ( !is_string($k) )
            throw new Exceptions\InvalidParameterException("'k' parameter is not a string.");
        
        return array_key_exists($k, $this->_params);
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
     * @throws Exceptions\InvalidParameterException Thrown if parameter $k is not a string
     */
	function testFileUpload($k)
	{
        if ( !is_string($k) )
            throw new Exceptions\InvalidParameterException("'k' parameter is not a string.");

        return !empty($this->_fileUploads[$k]);
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
     * @throws Exceptions\InvalidParameterException Thrown if parameter $k is not a string
     */
    public function get($k)
    {
        if ( !is_string($k) )
            throw new Exceptions\InvalidParameterException("'k' parameter is not a string.");
        
        return array_key_exists($k, $this->_params) ? $this->_params[$k] : null;
    }
    
    
    /** 
     * Get a file upload value
     * 
     * @param string $k Parameter name
     * @return FileUploadRequest FileUploadRequest object
     * @throws Exceptions\InvalidParameterException Thrown if parameter $k is not a string
     */
    public function getFileUpload($k)
    {
        if ( !is_string($k) )
            throw new Exceptions\InvalidParameterException("'k' parameter is not a string.");

        return array_key_exists($k, $this->_fileUploads) ? $this->_fileUploads[$k] : null;
    }
	
	
	/** 
	 * Get request as associative array
	 *
	 * @return string[]
	 */
	public function getRequestAsArray()
	{
		return $this->_params;
	}
	
	
	/**
	 * Get request method
	 *
	 * @return string
	 */
	public function getMethod()
	{
		return $this->_method;
	}
    
    
    /** 
     * Magic accessor
     * 
     * @param string $k Parameter name
     * @return string Parameter value
     * @throws Exceptions\InvalidParameterException Thrown if parameter $k is not a string
     */
    public function __get($k)
    {
        return $this->get($k);
    }
}



?>