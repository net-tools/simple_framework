<?php

namespace %%ns%%\Commands;


use \Nettools\Simple_Framework\Config\Config;
use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;



class %%defaultcmd%% extends Command
{
    /** 
     * Validate a request (mandatory parameters)
     * 
     * @param Request $r Request to validate
     * @return bool Returns true if request is valid
     */
    public function validateRequest(Request $r)
    {
        return true;
    }

	
	public function execute(Request $req, Application $app)
    {
		return $this->returnHTML('Html returned <b>value</b>');
	}
    
}


?>