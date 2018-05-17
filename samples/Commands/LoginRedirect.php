<?php

namespace Myapp\Commands;


use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;




class LoginRedirect extends Command
{
    public function execute(Request $req, Application $app)
    {
		$ctx = ['_i_' => 'my_user_id_here'];
        $app->controller->login($ctx, 'loginHome');
    }
    
}


?>