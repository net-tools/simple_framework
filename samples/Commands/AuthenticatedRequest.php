<?php

namespace Myapp\Commands;


use \Nettools\Simple_Framework\AuthenticatedCommand;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;




class AuthenticatedRequest extends AuthenticatedCommand
{

    public function execute(Request $req, Application $app)
    {
        return $this->returnHTML("<em>Authenticated command called with parameters <b>_h_={$req->_h_}</b> and <b>_i_={$req->_i_}</b></em>");
    }
    
}


?>