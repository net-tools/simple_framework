<?php

namespace Nettools\Simple_Framework\Tests;




use \Nettools\Simple_Framework\Request;




class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function testWrongParameters()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidParameterException::class);
		
		
        new Request('param');      // parameter 1 must be an array of strings or an object litteral
    }

    
    public function testWrongParameters2()
    {
     	$this->expectException(\Nettools\Simple_Framework\Exceptions\InvalidParameterException::class);

		
		new Request(array('k'=>'param'), 'kk');      // parameter 2 must be an array
    }
    

    public function testRequest()
    {
        $r = new Request(array('input0'=>'', 'input1'=>'value1', 'input2'=>'12'));
        $this->assertEquals(true, $r->test('input0'));
        $this->assertEquals(true, $r->test('input1'));
        $this->assertEquals(true, $r->test('input2'));
        $this->assertEquals(false, $r->test('input3'));

		
        $r = new Request((object)array('input0'=>'', 'input1'=>'value1', 'input2'=>'12'));
        $this->assertEquals(true, $r->test('input0'));
        $this->assertEquals(true, $r->test('input1'));
        $this->assertEquals(true, $r->test('input2'));
        $this->assertEquals(false, $r->test('input3'));
		
        
        $this->assertEquals(true, $r->testArray(array('input0', 'input1')));
        $this->assertEquals(false, $r->testArray(array('input2', 'input3')));
        
        $this->assertEquals('', $r->input0);
        $this->assertEquals('value1', $r->input1);
        $this->assertEquals('12', $r->input2);
        $this->assertEquals(NULL, $r->input3);
    }
    

}

?>