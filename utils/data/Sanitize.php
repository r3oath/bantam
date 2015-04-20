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

use \App\Utils\Input\Validate;

/**
* Sanitizes strings, making them safe for HTML, JSON, URL's etc.
* @package App\Utils\Data
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Sanitize {
    /**
    * Returns a HTML safe version of the given string.
    * @param string $string The string to sanitize.
    * @return string A HTML safe string.
    */
    public static function html($string) {
        return htmlspecialchars($string);
    }

    /**
    * Returns a JSON safe version of the given string.
    * @param string $string The string to sanitize.
    * @return string A JSON safe string.
    */
    public static function json($string) {
        return json_encode($string);
    }

    /**
    * Returns a URL safe version of the given string.
    * @param string $string The string to sanitize.
    * @return string A URL safe string.
    */
    public static function url($string) {
        return urlencode($string);
    }

    /**
    * Returns a sanitized and valid email address from the given string. If the
    * string does not contain a valid email address, the default value will be
    * returned instead.
    * @param string $string The string containing an email address to sanitize.
    * @param string $default The default value to return if there's no valid email adress in the given string.
    * @return string The sanitized email address or default value.
    */
    public static function email($string, $default='Invalid email address.') {
        $sanitized = filter_var($string, FILTER_SANITIZE_EMAIL);
        return Validate::email($sanitized) ? $sanitized : $default;
    }
}
