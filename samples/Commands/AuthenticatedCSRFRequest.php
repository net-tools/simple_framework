<?php

namespace Myapp\Commands;



use \Nettools\Simple_Framework\AuthenticatedCommand;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;





class AuthenticatedCSRFRequest extends AuthenticatedCommand
{

    public function execute(Request $req, Application $app)
    {
		$cookie = $app->controller->getCSRFSecurityHandler()->getCSRFCookie();
        return $this->returnHTML("<em>Authenticated CSRF command called with parameters <b>_CSRF_value_={$req->_CSRF_value_}</b>, <b>cookieCSRF=$cookie</b> and <b>value={$req->value}</b></em>");
    }
    
}


?>