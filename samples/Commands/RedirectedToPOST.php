<?php

namespace Myapp\Commands;


use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;




class RedirectedToPOST extends Command
{
    public function execute(Request $req, Application $app)
    {
        return $this->returnHTML("GET command transformed to a POST command with parameters param1='{$req->param1}' and param2='{$req->param2}'");
    }
    
}


?>