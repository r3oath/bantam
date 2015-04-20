<?php namespace App\Utils\User;

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

use \App\Utils\Data\Crypto;
use \App\Utils\Misc\Sundial;

/**
* Manages encrypted user cookies.
* @package App\Utils\User
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Cookies {
    /**
    * Create/set a new cookie in the users browser. This cookie will be automatically
    * encrypted to provide futher protection against someone stealing the users
    * cookie(s) and attempting to read out the content.
    * @param string $name The name of the cookie.
    * @param string $value The value for the cookie (will be encrypted).
    * @param int $expires When the cookie will expire, default is in 5 years time from the time it was set.
    * @return bool True if the cookie was set, false otherwise (does not indicate whether the user accepted the cookie though).
    */
    public static function set($name, $value, $expires=null) {
        // If no expirary set, expire 5 years into the future...
        if($expires === null) {
            $expires = Sundial::now()->years(5)->time();
        }

        $secure = isset($_SERVER['HTTPS']);
        $http_only = true;

        // Encrypt the cookie.
        $value_enc = Crypto::encrypt($value);
        $value_signed = Crypto::sign($value_enc);

        return setcookie($name, $value_signed, $expires, '/', '', $secure, $http_only);
    }

    /**
    * Get a cookie's value given the name. This will automatically decrypt the cookie
    * and return its contents in plain text form.
    * @param string $name The name of the cookie to get.
    * @param string $default The default value to return if this cookie does not exist.
    * @return string The cookie's value/content or the default value.
    */
    public static function get($name, $default='') {
        if(isset($_COOKIE[$name])) {
            $value_signed = $_COOKIE[$name];
            if(Crypto::verifySignature($value_signed) === true) {
                $value_enc = Crypto::stripSignature($value_signed);
                $value = Crypto::decrypt($value_enc);
                return $value;
            }
        }

        return $default;
    }

    /**
    * Check to see if the specified cookie exists.
    * @param string $name The name of the cookie to check for.
    * @return bool True if the cookie exists, false otherwise.
    */
    public static function exist($name) {
        return isset($_COOKIE[$name]);
    }
}
