<?php
/**
 * AuthenticatedCommand
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */




namespace Nettools\Simple_Framework;





/**
 * Base class for application authenticated command
 */
abstract class AuthenticatedCommand extends Command {

	/** 
	 * Does the command require authentication (through one or many SecurityHandlers\SecurityHandler objects) ?
	 *
	 * @return bool Returns true
	 */
	public function requiresAuthentication()
	{
		return true;
	}
    
    
}



?>