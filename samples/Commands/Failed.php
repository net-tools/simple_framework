<?php

namespace Myapp\Commands;


use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;




class Failed extends Command
{
    public function execute(Request $req, Application $app)
    {
        return $this->fail('For some unknown reason, this command is failing !');
    }
}


?>