<?php

namespace Myapp\Commands;


use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;




class Test1 extends Command
{
    // obtenir le planning
    public function execute(Request $req, Application $app)
    {
        return $this->returnHTML('<em>Command returned HTML content : <b style="text-decoration:underline">' . $req->value . '</b></em>');
    }
    
}


?>