<?php
/**
 * UnitTestApplication
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework;



/**
 * Class for application inside unit test
 */
class UnitTestApplication extends Application {

    /** 
     * Constructor
     *
     * @param string $ns Namespace used in the user application for commands
     * @param Registry $registry Registry object for application
	 * @param Request $req Request object
     */
    public function __construct($ns, Registry $registry, Request $req)
    {
        parent::__construct(new UnitTestController($ns, $req), $registry);
    }
	
	
	
	/**
	 * Helper static method to create an application object with a simple request set with an object litteral or an array
	 *
     * @param string $ns Namespace used in the user application for commands
     * @param Registry $registry Registry object for application
	 * @param \stdClass|string[] $req Request as an object litteral or an associative array ; does not support file upload
	 * @return Application Returns an Application object built with the Request
	 */
	static public function create($ns, Registry $registry, $req)
	{
		return new UnitTestApplication($ns, $registry, new Request($req));
	}
    
}



?>