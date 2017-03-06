<?php

namespace Myapp\Commands;


use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;




class Bad_code extends Command
{
    public function execute(Request $req, Application $app)
    {
        // this will raise a PHP exception since fun method is called on null !
        $x = null;
        $x->fun();
    }
}


?>