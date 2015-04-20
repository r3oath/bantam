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

/**
* Provides the ability to store configurable values and get their values at any stage.
* @package App\Utils\Misc
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Config {
    /**
    * @var array An array of config variables and respective values.
    */
    private static $config_map = array();

    /**
    * Set/Update a config key/value.
    * @param string $key The config key.
    * @param string $value The config value.
    * @return void
    */
    public static function set($key, $value) {
        static::$config_map[$key] = $value;
    }

    /**
    * Set/Update multiple config keys and values. Expects an associative array.
    * @param array $array The array of keys and values to set in the config map.
    * @return void
    */
    public static function create($array) {
        foreach ($array as $key => $value) {
            static::$config_map[$key] = $value;
        }
    }

    /**
    * Get a value from the config for the specified key.
    * @param string $key The key to return the value for.
    * @param string $default The default value to return if the key cannot be found.
    * @return void
    */
    public static function get($key, $default='') {
        if(isset(static::$config_map[$key])) {
            return static::$config_map[$key];
        }

        return $default;
    }
}
