<?php
/**
 * FileUploadRequest
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework;



/**
 * Class for file upload request (one file)
 */
class FileUploadRequest {
    
    /**
     * @var int Error code as provided by PHP
     */
    protected $_error = NULL;
    
    /**
     * @var string Path to the uploaded file, usually in the server tmp dir 
     */
    protected $_tmp_name = NULL;
    
    /**
     * @var int Size of upload in bytes
     */
    protected $_size = NULL;
    
    /**
     * @var string Mime-type of file
     */
    protected $_type = NULL;
    
    /**
     * @var string Filename on client computer (no path)
     */
    protected $_name = NULL;
    
    
    
    /** 
     * Constructor for FileUploadRequest
     *
     * @param int $error Error code
     * @param string $tmp_name Path to uploaded file
     * @param int $size File size uploaded
     * @param string $type Mime-type of file
     * @param string $name Filename of file uploaded
     */
    public function __construct($error, $tmp_name, $size, $type, $name)
    {
        if ( !is_int($error) )
            throw new Exceptions\InvalidParameterException('Parameter 1 of FileUploadRequest is not an int.');
        
        
        // check properties of file upload only if the upload has occured
        if ( $error == UPLOAD_ERR_OK )
        {
            if ( !is_string($tmp_name) )
                throw new Exceptions\InvalidParameterException('Parameter 2 of FileUploadRequest is not a string.');
            if ( !is_int($size) )
                throw new Exceptions\InvalidParameterException('Parameter 3 of FileUploadRequest is not an int.');
            if ( !is_string($type) )
                throw new Exceptions\InvalidParameterException('Parameter 4 of FileUploadRequest is not a string.');
            if ( !is_string($name) )
                throw new Exceptions\InvalidParameterException('Parameter 5 of FileUploadRequest is not a string.');
        }
        
        $this->_error = $error;
        $this->_tmp_name = $tmp_name;
        $this->_size = $size;
        $this->_type = $type;
        $this->_name = $name;
    }

    
	/** 
     * Magic method for property access
     *
     * @param string $k Property
     * @return mixed Property named $k
     */
	public function __get($k) { return $this->{"_$k"}; }
    
    
    /**
     * Test if file has been successfully uploaded
     *
     * @return bool Returns true if file has been uploaded
     */
	public function uploaded() { return $this->_error == UPLOAD_ERR_OK;}
	
    
    /**
     * Test if no file has been uploaded
     * 
     * @return bool Returns true if no file has been uploaded
     */
    public function no_file() { return $this->_error == UPLOAD_ERR_NO_FILE;}
	
    
    /** 
     * Test if upload has been successfull or if no file has been uploaded (that is to say, no error occured)
     *
     * @return bool Returns false if an error occured during upload, true otherwise
     */
    public function success() { return $this->uploaded() || $this->no_file(); }

    
    	
	/**
     * Move an upload file
     * 
     * @param string path New path/name to file 
     * @return bool Returns true if file has been moved successfully
     */
	public function moveUploadedFile($path)
	{
		return move_uploaded_file($this->_tmp_name, $path);
	}

}



?>