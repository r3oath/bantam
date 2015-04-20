<?php namespace App\Utils\Http;

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
* Deals with HTTP requests created by the user or server.
* @package App\Utils\Http
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Request {
    /**
    * Returns true if the current request is an HTTP GET.
    * @return bool
    */
    public static function isGet() {
        return isset($_SERVER['REQUEST_METHOD']) &&
            strtoupper($_SERVER['REQUEST_METHOD']) === 'GET';
    }

    /**
    * Returns true if the current request is an HTTP POST.
    * @return bool
    */
    public static function isPost() {
        if(isset($_SERVER['REQUEST_METHOD']) &&
           strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
            if(isset($_POST['_request']) && $_POST['_request'] === 'post') {
                return true;
            }
        }

        return false;
    }

    /**
    * Returns true if the current request is an HTTP PUT.
    * @return bool
    */
    public static function isPut() {
        if(isset($_SERVER['REQUEST_METHOD']) &&
           strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
            if(isset($_POST['_request']) && $_POST['_request'] === 'put') {
                return true;
            }
        }

        return false;
    }

    /**
    * Returns true if the current request is an HTTP DELETE.
    * @return bool
    */
    public static function isDelete() {
        if(isset($_SERVER['REQUEST_METHOD']) &&
           strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
            if(isset($_POST['_request']) && $_POST['_request'] === 'delete') {
                return true;
            }
        }

        return false;
    }

    /**
    * Check if the current request is of the given type.
    * @param string $type The type of request to check for, 'post', 'get', 'put' or 'delete'.
    */
    public static function isType($type) {
        switch ($type) {
            case 'post':
                return static::isPost() ? true : false;
            case 'get':
                return static::isGet() ? true : false;
            case 'put':
                return static::isPut() ? true : false;
            case 'delete':
                return static::isDelete() ? true : false;
            default:
                return false;
        }
    }

    /**
    * Returns a string representation of the current request type.
    * @return string The request type.
    */
    public static function getType() {
        if(static::isPost())   return 'post';
        if(static::isGet())    return 'get';
        if(static::isPut())    return 'put';
        if(static::isDelete()) return 'delete';
        return null;
    }

    /**
    * Returns an array containing the request path structure. For example, if
    * a user requests /users/bob, the array returned will contain ['users', 'bob'].
    * @param string $subfolder If specified, this subfolder will be excluded from the array.
    * @return mixed An array containing the request structure or null if no request structure can be found.
    */
    public static function capture($subfolder=null) {
        if(isset($_SERVER['PATH_INFO'])) {
            return explode("/", substr($_SERVER['PATH_INFO'], 1));
        }

        if(isset($_SERVER['REQUEST_URI'])) {
            if($subfolder === null) {
                return explode("/", substr($_SERVER['REQUEST_URI'], 1));
            } else {
                $mutated = trim(str_replace($subfolder, '', $_SERVER['REQUEST_URI']), '/');
                return explode("/", $mutated);
            }
        }

        return null;
    }

    /**
    * Returns an array of $_GET parameters and their associated values if they
    * are present in the allowed parameters list.
    * @param array $params The list of allowed parameters.
    * @return array
    */
    public static function allowedGetParams($params=[]) {
        $allowed = [];
        foreach ($params as $key) {
            if(isset($_GET[$key])) {
                $allowed[$key] = $_GET[$key];
            } else {
                $allowed[$key] = null;
            }
        }

        return $allowed;
    }

    /**
    * Returns an array of $_POST parameters and their associated values if they
    * are present in the allowed parameters list.
    * @param array $params The list of allowed parameters.
    * @return array
    */
    public static function allowedPostParams($params=[]) {
        $allowed = [];
        foreach ($params as $key) {
            if(isset($_POST[$key])) {
                $allowed[$key] = $_POST[$key];
            } else {
                $allowed[$key] = null;
            }
        }

        return $allowed;
    }
}
