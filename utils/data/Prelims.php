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

/**
* Provides basic preliminary data checking, useful for processing user input etc.
* <code>
* // Will return true.
* Prelims::hasNulls(['User', 'Password', null]);
*
* // Will return true, string length is between 5 and 10.
* Prelims::hasLength('Tristan', ['min' => 5, 'max' => 10]);
*
* // Will return the value of 5 as value is below min.
* Prelims::clamp(3, 5, 10);
* </code>
* @package App\Utils\Data
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Prelims {
    /**
    * Checks to see if the given array contains any null values. Helpful when checking
    * an array of get/post parameters for any missing fields.
    * @param array $array The single dimension array containing possible null values.
    * @return bool True if the array contains any null values, false otherwise.
    */
    public static function hasNulls($array=[]) {
        foreach ($array as $key => $value) {
            if($value == null) {
                return true;
            }
        }

        return false;
    }

    /**
    * Checks whether the given string has a specific length given in the options.
    * @param string $param The string to check.
    * @param array $options 'min' => The minimum length, 'max' => The maximum length, 'exact' => The exact length.
    * @return bool True if the string matches the options provided, false otherwise.
    */
    public static function hasLength($param, $options=[]) {
        if(isset($options['min']) && (strlen($param) < (int)$options['min'])) {
            return false;
        }

        if(isset($options['max']) && (strlen($param) > (int)$options['max'])) {
            return false;
        }

        if(isset($options['exact']) && (strlen($param) != (int)$options['exact'])) {
            return false;
        }

        return true;
    }

    /**
    * Checks whether the given string is numeric and has the specified range given in the options.
    * @param string $param The string to check.
    * @param array $options 'min' => The minimum value, 'max' => The maximum value, 'exact' => The exact value.
    * @return bool True if the value falls within the given options, false otherwise.
    */
    public static function isNumeric($param, $options=[]) {
        if(!is_numeric($param)) {
            return false;
        }

        $numericValue = $param + 0;

        if(isset($options['min']) && ($numericValue < (int)$options['min'])) {
            return false;
        }

        if(isset($options['max']) && ($numericValue > (int)$options['max'])) {
            return false;
        }

        if(isset($options['exact']) && ($numericValue != (int)$options['exact'])) {
            return false;
        }

        return true;
    }

    /**
    * Clamps the specified value between the specified minimum and maximum values. For
    * example clamp(3, 5, 10) will return 5, clamp(8, 3, 5) will return 5 and clamp(3, 2, 5) will return 3.
    * @param numeric $value The value to check.
    * @param numeric $min The minimum value.
    * @param numeric $max The maximum value.
    * @return numeric The original value clamped between min and max.
    */
    public static function clamp($value, $min, $max) {
        return ($value > $max) ? $max : ($value < $min) ? $min : $value;
    }

    /**
    * Check whether the specified string starts with the given substring.
    * @param string $string The string to check.
    * @param string $needle The needle to look for.
    * @return bool True if string starts with substring, false otherwise.
    */
    public static function startsWith($string, $needle) {
        return substr($string, 0, strlen($needle)) === $needle;
    }

    /**
    * Check whether the specified string ends with the given substring.
    * @param string $string The string to check.
    * @param string $needle The needle to look for.
    * @return bool True if string ends with substring, false otherwise.
    */
    public static function endsWith($string, $needle) {
        return substr($string, -strlen($needle)) === $needle;
    }

    /**
    * Takes a delimited string and retuns a clean (trimmed) array of each element.
    * For example '1, 2, 3' would return ['1', '2', '3'].
    * @param string $list The delimited string to process.
    * @param string $delim The delimiter to break the string up on.
    * @return array An array of all items in the string.
    */
    public static function cleanList($list, $delim) {
        $parts = explode($delim, $list);
        $clean = array();
        foreach ($parts as $part) {
            $clean[] = trim($part);
        }

        return $clean;
    }
}
