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

    public static function url($url, $protos=array('http', 'https'), $dns=false) {
        if(Prelim::strNullOrEmpty($url)) {
            return false;
        }

        if(!is_array($protos) || count($protos) < 1 || !is_bool($dns)) {
            return false;
        }

        $has_proto = (strpos($url, '://') !== false);
        if($has_proto === true) {
            $match = false;
            foreach ($protos as $proto) {
                if(!Prelim::strNullOrEmpty($proto) && Prelim::startsWith($url, $proto)) {
                    $match = true;
                }
            }

            if($match === false) {
                return false;
            }
        }

        // First come, first serve.
        if($has_proto === false) {
            $url = $protos[0].'://'.$url;
        }

        $exp = sprintf(self::URL_PATTERN, implode('|', $protos));
        if(!static::regex($exp, $url)) {
            return false;
        }

        if($dns === true) {
            $host = parse_url($url, PHP_URL_HOST);
            if(!checkdnsrr($host, 'ANY')) {
                return false;
            }
        }

        return true;
    }

    public static function ip($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP, array(
            'flags' =>
            FILTER_FLAG_IPV4 |
            FILTER_FLAG_IPV6 |
            FILTER_FLAG_NO_PRIV_RANGE |
            FILTER_FLAG_NO_RES_RANGE)) !== false;
    }

    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function bool($bool) {
        if($bool === null) {
            return false;
        }

        if(is_string($bool) && strlen($bool) < 1) {
            return false;
        }

        return filter_var($bool, FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE) !== null;
    }

    public static function age($age) {
        if($age === null || !is_numeric($age)) {
            return false;
        }

        return ((int)$age >= 1 && (int)$age <= 130);
    }

    public static function int($int) {
        return filter_var($int, FILTER_VALIDATE_INT,
            array('flags' =>
                FILTER_FLAG_ALLOW_OCTAL |
                FILTER_FLAG_ALLOW_HEX)) !== false;
    }

    public static function float($float) {
        return filter_var($float, FILTER_VALIDATE_FLOAT,
            FILTER_FLAG_ALLOW_THOUSAND) !== false;
    }

    public static function regex($exp, $str) {
        if(Prelim::strNullOrEmpty($exp) || Prelim::strNullOrEmpty($str)) {
            return false;
        }

        $match = @preg_match($exp, $str);
        return ($match !== 0 && $match !== false);
    }
}
