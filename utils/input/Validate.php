<?php namespace App\Utils\Input;

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
* Validates various data types.
* @package App\Utils\Input
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Validate {
    /**
    * Borrowed from Symphony's UrlValidator
    * https://github.com/symfony/Validator/blob/master/Constraints/UrlValidator.php
    * Author: Bernhard Schussek <bschussek@gmail.com>
    */
    const URL_PATTERN = '~^
        (%s)://                                 # protocol
        (([\pL\pN-]+:)?([\pL\pN-]+)@)?          # basic auth
        (
            ([\pL\pN\pS-\.])+(\.?([\pL]|xn\-\-[\pL\pN-]+)+\.?) # a domain name
                |                                              # or
            \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}                 # a IP address
                |                                              # or
            \[
                (?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
            \]  # a IPv6 address
        )
        (:[0-9]+)?                              # a port (optional)
        (/?|/\S+)                               # a /, nothing or a / with something
    $~ixu';

    /**
    * Validates the given string is an email address.
    * @param string $string String to validate.
    * @return bool True if valid, false otherwise.
    */
    public static function email($string) {
        return filter_var($string, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
    * Validates the given string is a boolean value. Allowed bool types are the same
    * as specified by the PHP FILTER_VALIDATE_BOOLEAN filter.
    * @param string $string String to validate.
    * @return bool True if valid, false otherwise.
    */
    public static function bool($string) {
        return filter_var($string, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null;
    }

    /**
    * Validates the given string is a IPV4 or IPV6 address outside of private ranges.
    * @param string $string String to validate.
    * @return bool True if valid, false otherwise.
    */
    public static function ip($string) {
        return filter_var($string, FILTER_VALIDATE_IP, array(
                          'flags' =>
                          FILTER_FLAG_IPV4 |
                          FILTER_FLAG_IPV6 |
                          FILTER_FLAG_NO_PRIV_RANGE |
                          FILTER_FLAG_NO_RES_RANGE)) !== false;
    }

    /**
    * Validates the given string is a floating point number, thousands seperator permitted.
    * @param string $string String to validate.
    * @return bool True if valid, false otherwise.
    */
    public static function float($string) {
        return filter_var($string, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND) !== false;
    }

    /**
    * Validates the given string is an integer.
    * @param string $string String to validate.
    * @return bool True if valid, false otherwise.
    */
    public static function int($string) {
        return filter_var($string, FILTER_VALIDATE_INT,
                          array('flags' =>
                                FILTER_FLAG_ALLOW_OCTAL |
                                FILTER_FLAG_ALLOW_HEX)) !== false;
    }

    /**
    * Validates the given string is a URL with optionally having the types of protocols
    * allowed as well as a DNS check to verify the URL is active.
    * @param string $string String to validate.
    * @param array $allowed_protocols An array of allowed protocols, eg: ['http', 'https', 'ftp'].
    * @param bool $dns_check Whether to perform a DNS check on the URL.
    * @return bool True if valid, false otherwise.
    */
    public static function url($string, $allowed_protocols=null, $dns_check=false) {
        if($string === null || trim($string) === '') {
            return false;
        }

        if($allowed_protocols == null) {
            $allowed_protocols = array('http', 'https');
        }

        $string = static::conformUrl($string, $allowed_protocols);

        $pattern = sprintf(self::URL_PATTERN, implode('|', $allowed_protocols));

        if(!static::custom($string, $pattern)) {
            return false;
        }

        if($dns_check === true) {
            $host = parse_url($string, PHP_URL_HOST);
            if(!checkdnsrr($host, 'ANY')) {
                return false;
            }
        }

        return true;
    }

    private static function conformUrl($string, $allowed_protocols) {
        if(count($allowed_protocols) < 1) {
            return $string;
        }

        $has_proto = false;
        foreach ($allowed_protocols as $key => $value) {
            if(strpos($string, $value) === 0) {
                $has_proto = true;
            }
        }

        if($has_proto === false) {
            return $allowed_protocols[0].'://'.$string;
        }
    }

    /**
    * Validates the given string conforms to the specified regex expression.
    * @param string $string String to validate.
    * @param string $regex The regex expression to use, eg: '/\A([a-z]+)\Z/'.
    * @return bool True if valid, false otherwise.
    */
    public static function custom($string, $regex) {
        $match = preg_match($regex, $string);
        return ($match !== 0 && $match !== false);
    }
}
