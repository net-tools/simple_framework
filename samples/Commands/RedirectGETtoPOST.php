<?php

namespace Myapp\Commands;


use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;




class RedirectGETtoPOST extends Command
{
    public function execute(Request $req, Application $app)
    {
        return $this->returnNull();
    }
    
}


?>