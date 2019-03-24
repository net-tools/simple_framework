<?php

namespace Myapp\Commands;


use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;




class RevokeCSRF extends Command
{

    public function execute(Request $req, Application $app)
    {
		$sech = $app->controller->getCSRFSecurityHandler();
		$sech->revokeCSRF();
        return $this->returnHTML("<em>CSRF layer revoked</em>");
    }
    
}


?>