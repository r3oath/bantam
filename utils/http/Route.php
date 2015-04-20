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

use \Closure;

/**
* Provides basic URL routing and URL token extraction.
* <code>
* // Register a new GET request for users/:name, grabbing
* // the name provided and printing it out.
*
* Route::get('users/:name', function(){
*     $name = Route::getToken('name');
*     echo "Hello {$name}";
* });
* </code>
* @package App\Utils\Http
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Route {
    /**
    * @var array $routes A table of all registered routes and their properties.
    */
    private static $routes = array();

    /**
    * @var array $route_tokens An array of all captured route tokens and thier values.
    */
    private static $route_tokens = array();

    /**
    * Registers a new route internally.
    * @param string $req_type The type of request this route handles.
    * @param string $signature The signature of this route.
    * @param mixed $closure The callback function for this route.
    * @param array $regexs An array of tokens and their corresponding regexs to match against.
    * @return void.
    */
    private static function register($req_type, $signature, $closure, $regexs) {
        if(!isset(static::$routes[$req_type])) {
            $routes[$req_type] = array();
        }

        static::$routes[$req_type][] = array(
            'signature' => $signature,
            'closure' => $closure,
            'regexs' => $regexs,
        );
    }

    /**
    * Registers a new route that will handle a POST request.
    * @param string $signature The signature of this route.
    * @param mixed $closure The callback function for this route.
    * @param array $regexs An array of tokens and their corresponding regexs to match against.
    * @return void.
    */
    public static function post($signature, $closure, $regexs=[]) {
        static::register('post', $signature, $closure, $regexs);
    }

    /**
    * Registers a new route that will handle a GET request.
    * @param string $signature The signature of this route.
    * @param mixed $closure The callback function for this route.
    * @param array $regexs An array of tokens and their corresponding regexs to match against.
    * @return void.
    */
    public static function get($signature, $closure, $regexs=[]) {
        static::register('get', $signature, $closure, $regexs);
    }

    /**
    * Registers a new route that will handle a PUT request.
    * @param string $signature The signature of this route.
    * @param mixed $closure The callback function for this route.
    * @param array $regexs An array of tokens and their corresponding regexs to match against.
    * @return void.
    */
    public static function put($signature, $closure, $regexs=[]) {
        static::register('put', $signature, $closure, $regexs);
    }

    /**
    * Registers a new route that will handle a DELETE request.
    * @param string $signature The signature of this route.
    * @param mixed $closure The callback function for this route.
    * @param array $regexs An array of tokens and their corresponding regexs to match against.
    * @return void.
    */
    public static function delete($signature, $closure, $regexs=[]) {
        static::register('delete', $signature, $closure, $regexs);
    }

    /**
    * Handles the given request by finding any matching routes in the routing table and executing
    * their callback functions.
    * @param array $request The request array, usually a product of Request::capture().
    * @return mixed True if the request was handled by a route, false otherwise.
    */
    public static function handle($request) {
        // Make sure there is at least 1 route type to handle this request.
        if(!isset(static::$routes[Request::getType()])) {
            return false;
        }

        // Get only the routes that handle this request type.
        $routes = static::$routes[Request::getType()];

        // Iterate over every entry in our routing table to find a possible
        // match to the given request.
        foreach ($routes as $route) {
            // Check to see if the request and signature have the same number
            // of constants and tokens, then proceed.
            $sig_parts = explode('/', $route['signature']);
            if(count($request) === count($sig_parts)) {
                // Check each signature constant matches the contant given
                // in the route and record any token values.
                $matches = 0;
                for ($i = 0; $i < count($request); $i++) {
                    if($sig_parts[$i] == $request[$i]) {
                        $matches += 1;
                    }
                    // If we've got ourselves a token here, remove the prepending :
                    // marker and add it's corresponding value from the request into
                    // the token array to be used later by the user.
                    if(strstr($sig_parts[$i], ':') !== false) {
                        static::$route_tokens[substr($sig_parts[$i], 1)] = $request[$i];
                        $matches += 1;
                    }
                }

                // If we found the right number of constants and tokens, we have
                // found a matching route.
                if($matches === count($sig_parts)) {

                    // Check to make sure that each token matches it's given regex (if provided).
                    $failure = false;
                    if(isset($route['regexs']) && count($route['regexs']) > 0) {
                        // Step through each of the token regex entries.
                        foreach ($route['regexs'] as $key => $value) {
                            // Check to see if this token has a corresponding regex.
                            if(array_key_exists($key, static::$route_tokens)) {
                                // Match the token value against the regex.
                                $result = preg_match("/\A{$value}\Z/", static::$route_tokens[$key]);
                                // Mark failure on failure.
                                if($result === false || $result === 0) {
                                    $failure = true;
                                }
                            }
                        }
                    }

                    // Fail if the tokens did not match their respective regexs.
                    if($failure === true) {
                        static::$route_tokens = array();
                        return false;
                    }

                    // Run the user specified code here.
                    ob_start();
                    if($route['closure'] instanceOf Closure) {
                        $route['closure']();
                    } else if(is_callable($route['closure'])) {
                        call_user_func($route['closure']);
                    } else {
                        throw new \ErrorException('Unknown type of callback function for route.');
                    }
                    ob_end_flush();

                    // Return success.
                    return true;
                }
            }
        }

        // No matching routes were found for the given request.
        static::$route_tokens = array();
        return false;
    }

    /**
    * Returns the available captured tokens for this request.
    * @return array The available tokens.
    */
    public static function getTokens() {
        return static::$route_tokens;
    }

    /**
    * Find and return the specified token from the available captured tokens for this request.
    * @param string $key The specific token to look for.
    * @return mixed The string token if available, otherwise null.
    */
    public static function getToken($key) {
        if(isset(static::$route_tokens[$key])) {
            return static::$route_tokens[$key];
        }

        return null;
    }
}
