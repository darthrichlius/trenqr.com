<?php

//require_once __DIR__."/../../../sb.env.php";
require_once __DIR__."/../../../../vendor/autoload.php";

use RODE\Sandbox\PHPUnit\TestsBundles\Math;

class MathTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp() {
        echo Exception::class;
    }
    
    protected function tearDown() {
        
    }
    
    public function testDoubleTrue(){
        $this->assertEquals(4, Math::double(2));
    }
    
    public function testDoubleFalse(){
        $this->assertEquals(7, Math::double(2));
    }

}
