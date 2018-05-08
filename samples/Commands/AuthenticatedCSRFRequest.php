<?php

namespace Myapp\Commands;


use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\AuthenticatedCommand;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;
use \Nettools\Simple_Framework\SecurityHandlers\CSRFSecurityHandler;




class AuthenticatedCSRFRequest extends AuthenticatedCommand
{
    // obtenir le planning
    public function execute(Request $req, Application $app)
    {
		$sech = $app->controller->getSecurityHandler(CSRFSecurityHandler::class, $app)->getSecureRequestHelper();
		$cookie = $sech->getCSRFCookie();
        return $this->returnHTML("<em>Authenticated CSRF command called with parameters <b>_CSRF_value_={$req->_CSRF_value_}</b>, cookieCSRF=<b>$cookie</b> and <b>value={$req->value}</b></em>");
    }
    
}


?>