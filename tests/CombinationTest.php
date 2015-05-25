<?php
require_once __DIR__.'/../vendor/autoload.php';

use Algorithm\Combination;

class CombinationTest extends \PHPUnit_Framework_TestCase {

    public function testNumberOfGenerations(){
        $entries = array(
            array('a'),
            array('b','c')
        );
        $c = Combination::combinations($entries);
        $this->assertCount(2, $c);
        $this->assertTrue($c === array(array('', 'a', 'b'), array('', 'a','c')));

        $entries[] = array('e','f','g');
        $c = Combination::combinations($entries);
        $this->assertCount(6, $c);
        $this->assertTrue($c === array(
            array('', 'a', 'b', 'e'), 
            array('', 'a', 'b', 'f'), 
            array('', 'a', 'b', 'g'), 
            array('', 'a', 'c', 'e'), 
            array('', 'a', 'c', 'f'), 
            array('', 'a', 'c', 'g')
            )
        );
    }
}
