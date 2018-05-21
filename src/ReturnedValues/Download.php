<?php
/**
 * Download
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework\ReturnedValues;





/**
 * Class for a Download value returned by a command
*/
abstract class Download extends Value {
    
    // value or file to be outputed to the browser for user download
    protected $_value = NULL;
    protected $_contentType = NULL;
    protected $_filename = NULL;
    
	
    
    /** 
     * Constructor of Download value
     * 
     * @param string $value Download filename or string value to be downloaded
	 * @param string $filename Filename suggested to user
	 * @param string $contentType File content-type
     */
    public function __construct($value, $filename, $contentType = 'application/octet-stream')
    {
        parent::__construct(true);
        $this->_value = $value;
        $this->_filename = $filename;
        $this->_contentType = $contentType;
    }
	
	
	
	/** 
	 * Getter for contentType
	 * 
	 * @return string
	 */
	public function getContentType()
	{
		return $this->_contentType;
	}
	
	
	
	/** 
	 * Getter for value
	 * 
	 * @return string
	 */
	public function getValue()
	{
		return $this->_value;
	}
	
	
	
	/** 
	 * Getter for fileName
	 * 
	 * @return string
	 */
	public function getFilename()
	{
		return $this->_filename;
	}
}



?>