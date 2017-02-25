<?php
/**
 * WebApplication
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework;



/**
 * Class for web application
 */
class WebApplication extends Application {

    /** 
     * Constructor
     *
     * @param string $ns Namespace used in the user application for commands
     * @param Registry $registry Registry object for application
     */
    public function __construct($ns, Registry $registry)
    {
        parent::__construct(new WebController($this, $ns), $registry);
    }
    
}



?>