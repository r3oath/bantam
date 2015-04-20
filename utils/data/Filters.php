<?php namespace App\Utils\Data;

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
* Allows the system to pass data to a set of registered filter callbacks that
* will optionally modify and return that data back to the system. An example would
* be when creating a user, passing the username to a filter which will add some
* uniqueness to the name, or prepending it with a company name etc.
* @package App\Utils\Data
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Filters {
    /**
    * @var array An array of filter names and respective callback functions.
    */
    private static $filters;

    /**
    * Registers a new filer and callback function.
    * @param string $name The name of the filter.
    * @param mixed $closure The callback function to associate with this filter.
    * @return void.
    */
    public static function register($name, $closure) {
        if(!isset(static::$filters[$name])) {
            static::$filters[$name] = array();
        }

        static::$filters[$name][] = $closure;
    }

    /**
    * Runs the specified filter, returning the (possibly) mutated data provided.
    * @param string $name The name of the filter to run.
    * @param mixed $data The data to pass to all the registered callback functions.
    * @return mixed The (possibly) mutated data.
    */
    public static function run($name, $data) {
        if(!isset(static::$filters[$name])) {
            return;
        }

        foreach (static::$filters[$name] as $key => $value) {
            if($value instanceOf Closure) {
                $data = $value($data);
            } else if(is_callable($value)) {
                $data = call_user_func($value, $data);
            }
        }

        return $data;
    }
}
