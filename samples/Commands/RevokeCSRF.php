<?php

namespace Myapp\Commands;


use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;
use \Nettools\Simple_Framework\SecurityHandlers\CSRFSecurityHandler;




class RevokeCSRF extends Command
{
    // obtenir le planning
    public function execute(Request $req, Application $app)
    {
		$sech = $app->controller->getSecurityHandler(CSRFSecurityHandler::class, $app)->getSecureRequestHelper();
		$sech->revokeCSRF();
        return $this->returnHTML("<em>CSRF layer revoked</em>");
    }
    
}


?>