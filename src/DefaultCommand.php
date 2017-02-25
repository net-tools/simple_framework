<?php
/**
 * DefaultCommand
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework;




/**
 * Class for default command
 */
class DefaultCommand extends Command {
    
    public function execute(Request $req, Application $app)
    {
        // default command returns a NULL value
        return $this->returnNull();
    }    
    
}



?>