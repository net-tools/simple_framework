<?php

namespace Myapp\Commands;


use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;




class InitCSRF extends Command
{

    public function execute(Request $req, Application $app)
    {
		$sech = $app->controller->getCSRFSecurityHandler();
		$sech->initializeCSRF();
		$cookie = $sech->getCSRFCookie();
		
        return $this->returnHTML("<em>CSRF layer initialized with cookie : <b>$cookie</b></em>");
    }
    
}


?>