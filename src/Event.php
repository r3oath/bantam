<?php namespace r3oath\bantam;

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

require_once 'Prelim.php';
use \Closure;

class Event {
    private static $events = array();

    public static function register($name, $fn) {
        if(Prelim::strNullOrEmpty($name)) {
            return false;
        }

        if($fn instanceOf Closure) {
            static::$events[$name][] = $fn;
            return true;
        }

        return false;
    }

    public static function fire($name, $data=null) {
        if(Prelim::strNullOrEmpty($name)) {
            return false;
        }

        if(isset(static::$events[$name])) {
            $fired = 0;
            foreach (static::$events[$name] as $fn) {
                if($fn instanceOf Closure) {
                    $fn($data);
                    $fired++;
                }
            }

            return $fired;
        }

        return false;
    }
}
