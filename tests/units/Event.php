<?php namespace r3oath\bantam\tests\units;

include_once 'src/Event.php';

use \mageekguy\atoum;
use \r3oath\bantam;

class Event extends atoum\test {
    function userFunc() {
        return null;
    }

    function testRegister() {
        // Edge cases.
        $this->variable(bantam\Event::register(null, function(){}))
            ->isEqualTo(false);
        $this->variable(bantam\Event::register(123, function(){}))
            ->isEqualTo(false);
        $this->variable(bantam\Event::register(true, function(){}))
            ->isEqualTo(false);
        $this->variable(bantam\Event::register('test', null))
            ->isEqualTo(false);
        $this->variable(bantam\Event::register('test', 123))
            ->isEqualTo(false);
        $this->variable(bantam\Event::register('test', false))
            ->isEqualTo(false);
        $this->variable(bantam\Event::register('test', 'notfunc'))
            ->isEqualTo(false);

        // Normal use.
        $this->variable(bantam\Event::register('test', function(){}))
            ->isEqualTo(true);
        $this->variable(bantam\Event::register('test', function($args){}))
            ->isEqualTo(true);
        $this->variable(bantam\Event::register('test', array($this, 'userFunc')))
            ->isEqualTo(true);
    }

    function testFire() {
        // Setup.
        bantam\Event::register('test', function(){ return null; });
        bantam\Event::register('test2', function(){ return null; });
        bantam\Event::register('test2', function(){ return null; });

        // Edge cases.
        $this->variable(bantam\Event::fire(null))->isEqualTo(false);
        $this->variable(bantam\Event::fire(123))->isEqualTo(false);
        $this->variable(bantam\Event::fire(false))->isEqualTo(false);

        // Normal use.
        // We unfortunately cannot test that the function(s) registered
        // for the event 'test' actually did anything, but the fact
        // that fire returns a number means >= 0 means it ran correctly.
        $this->variable(bantam\Event::fire('test'))->isEqualTo(1);
        $this->variable(bantam\Event::fire('test', 'some data'))->isEqualTo(1);
        $this->variable(bantam\Event::fire('test2', 'some data'))->isEqualTo(2);
        $this->variable(bantam\Event::fire('notexist'))->isEqualTo(0);
    }
}
