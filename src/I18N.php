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

class I18N {
    private static $table = array();
    private static $locale = 'en';

    public static function load($src, $arr=null) {
        if(Prelim::strNullOrEmpty($src)) {
            return false;
        }

        if($arr !== null && (!is_array($arr) || count($arr) < 1)) {
            return false;
        }

        if(is_file($src)) {
            $arr = require($src);
            $locale = explode('.', basename($src));
            return static::parse($locale[0], $arr);
        } else {
            return static::parse($src, $arr);
        }

        return false;
    }

    private static function parse($src, $arr) {
        // Scan the language definition for any errors.
        foreach ($arr as $pack => $lang) {
            if(Prelim::strNullOrEmpty($pack)) {
                return false;
            }

            if(!is_array($lang) || (is_array($lang) && count($lang) < 1)) {
                return false;
            }

            foreach ($lang as $key => $str) {
                if(Prelim::strNullOrEmpty($key) || Prelim::strNullOrEmpty($str)) {
                    return false;
                }
            }
        }

        // We're ok, add it.
        static::$table[$src] = $arr;
        return true;
    }

    public static function e($token, $vars=null) {
        echo static::r($token, $vars);
    }

    public static function r($token, $vars=null) {
        if(Prelim::strNullOrEmpty($token)) {
            return '';
        }

        if($vars !== null && !is_array($vars)) {
            return '';
        }

        $parts = explode('.', $token);
        if(count($parts) !== 2) {
            return '';
        }

        // Get the token and string.
        $t = $parts[0];
        $s = $parts[1];

        if(!isset(static::$table[static::$locale][$t][$s])) {
            return '';
        }

        // Get the localised string.
        $str = static::$table[static::$locale][$t][$s];

        if($vars !== null && count($vars) > 0) {
            $c = 0;
            foreach ($vars as $var) {
                $str = str_replace("{{$c}}", $var, $str);
                $c++;
            }
        }

        return $str;
    }

    public static function setLocale($loc='en') {
        if(Prelim::strNullOrEmpty($loc)) {
            $loc = 'en';
        }

        static::$locale = $loc;
    }

    public static function getLocale() {
        return static::$locale;
    }
}
