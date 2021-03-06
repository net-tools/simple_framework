<?php

// no user namespace for this special test !


use \Nettools\Simple_Framework\Request;




    
class HashSecurityHandlerTest extends \PHPUnit\Framework\TestCase
{
    public function testSH()
    {
        $sh = new \Nettools\Simple_Framework\SecurityHandlers\HashSecurityHandler('my secret', 'H', 'I');
		$this->assertEquals('string', gettype($sh->makeHash('client_id', 'my secret')));
		$this->assertEquals(hash('sha256', 'client_id!my secret'), $sh->makeHash('client_id', 'my secret'));
		
		$r = new Request(['H'=>hash('sha256', 'client_id!my secret'), 'I'=>'client_id']);
		$this->assertEquals(true, $sh->check($r));
		
		$r = new Request(['H'=>hash('sha256', 'client_id!my secret'), 'I'=>'other_id']);
		$this->assertEquals(false, $sh->check($r));
		
		$r = new Request(['H'=>hash('sha256', 'client_id!my other secret'), 'I'=>'client_id']);
		$this->assertEquals(false, $sh->check($r));
    }
    
   
}

?>