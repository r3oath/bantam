<?php namespace App\Utils\Misc;

// The MIT License (MIT)

// Copyright (c) 2015 Tristan Strathearn

// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:

// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.

// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.

// Don't squawk.
!defined('BANTAM') ? exit() : null;

use \Closure;

/**
* Provides the ability to register and fire events, usefull if you want to seperate
* out your core functionality and your additional (such as plugins).
* <code>
* // Registers a new function for the 'user_registered'
* // event (inside a plugin file for example).
* Events::register('user_registered', function($args){
*     $user_name = $args[0]; // Would store 'Bob123', see below.
* });
*
* // Fires the 'user_registerd' event from your core code, optionally passing in
* // arguments to all the registered functions for this particular event.
* Events::fire('user_registered', 'Bob123');
* </code>
* @package App\Utils\Misc
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Events {
    /**
    * @var array An array of event names and respective regsitered callback functions.
    */
    private static $events = array();

    /**
    * Registers a new event and a corresponding callback function. The callback
    * can either be passed in as a Closure or as a function string.
    * @param string $name The name of the event to register.
    * @param mixed $closure The Closure or user function to call on this event.
    * @return void
    */
    public static function register($name, $closure) {
        if(!isset(static::$events[$name])) {
            static::$events[$name] = array($closure);
            return;
        }

        static::$events[$name][] = $closure;
    }

    /**
    * Fire the specified event and pass along the corresponding arguments to the
    * registered callback functions. The format for this functions arguments are
    * 'event_name' [, arg1[, arg2]]. For example, firing the event 'system_started'
    * and the time would look like fire('system_started', time()). Any arguments
    * passed to this function after the event name will be passed to the registered
    * callback functions as an array.
    * @return void
    */
    public static function fire() {
        // Get the arguments passed to us.
        $args = func_get_args();
        $num = count($args);

        // If not at least 1, exit silently.
        if($num < 1) {
            return;
        }

        // The first argument should be our event name.
        $event = $args[0];
        if(!isset(static::$events[$event])) {
            return;
        }

        // If more than one argument, prepare the rest to be passed to our
        // event callback function.
        if($num !== 1) {
            unset($args[0]);
            $args = array_values($args);
        }

        // Fire each callback function registered for this event.
        foreach (static::$events[$event] as $key => $func) {
            if($func instanceOf Closure) {
                if($num === 1) {
                    $func();
                } else {
                    $func($args);
                }
            } else if(is_callable($func)) {
                if($num === 1) {
                    call_user_func($func);
                } else {
                    call_user_func($func, $args);
                }
            } else {
                continue;
            }
        }
    }
}
