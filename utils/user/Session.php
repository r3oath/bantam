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

use \App\Utils\Misc\Sundial;
use \App\Utils\Misc\Config;

/**
* Provides basic user session management.
* @package App\Utils\User
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Session {
    /**
    * Starts a new session and stores the users signature, or simple re-opens the
    * current session.
    * @return void
    */
    public static function start() {
        if(session_id() == '') {
            session_start();
        }

        if(!isset($_SESSION['session_obj'])) {
            $_SESSION['session_obj'] = new SessionObj(static::signature());
        }
    }

    /**
    * Creates and returns a new user signature based on the REMOTE_ADDR and the
    * HTTP_X_FORWARDED_FOR server headers if available.
    * @return string The MD5 signature.
    */
    private static function signature() {
        return md5(
                   @$_SERVER['REMOTE_ADDR'].
                   @$_SERVER['HTTP_X_FORWARDED_FOR'].
                   @$_SERVER['HTTP_USER_AGENT']);
    }

    /**
    * Destroys the current session.
    * @return void
    */
    public static function destroy() {
        unset($_SESSION['session_obj']);
        $oid = session_id();
        session_unset($oid);
        session_destroy();
    }

    /**
    * Reset the current session, which simply destroys the current session
    * and begins a new session.
    * @return void
    */
    public static function reset() {
        static::destroy();
        static::start();
    }

    /**
    * Returns true if the current session is valid, false otherwise.
    * @return bool True if session is valid, false otherwise.
    */
    public static function valid() {
        return isset($_SESSION['session_obj']) && $_SESSION['session_obj']->valid(static::signature());
    }

    /**
    * Set a new session variable.
    * @param string $key The name of the variable.
    * @param string $value The value of the variable.
    * @return void
    */
    public static function set($key, $value) {
        $_SESSION['session_obj']->set($key, $value);
    }

    /**
    * Get a session variable from the session data or return a default value.
    * @param string $key The variable name to return a value for.
    * @param string $default The default value to return if variable not found.
    * @return mixed The variable value, or default value if not found.
    */
    public static function get($key, $default='') {
        return $_SESSION['session_obj']->get($key, $default);
    }

    /**
    * Checks to see if the current session has the specified variable set.
    * @param string $key The variable to check for.
    * @return bool True if variable exists, false otherwise.
    */
    public static function has($key) {
        return $_SESSION['session_obj']->has($key);
    }

    /**
    * Checks to see if the current session has already begun.
    * @return bool True if the session has begun, false otherwise.
    */
    public static function hasBegun() {
        return isset($_SESSION['session_obj']);
    }
}

/**
* Stores current session data.
*/
class SessionObj {
    private $data;
    private $signature;
    private $time_stamp;

    /**
    * Creates a new session object with the specified user signature.
    */
    public function __construct($signature) {
        $this->data = array();
        $this->signature = $signature;
        $this->touch();
    }

    private function touch() {
        $this->time_stamp = Sundial::now()->time();
    }

    /**
    * Alias for Session::isValid
    */
    public function valid($signature) {
        if(Config::get('bantam_session_max_idle_time', null) !== null) {
            $max_life = intval(Config::get('bantam_session_max_idle_time'));
            if(Sundial::now()->difference($this->time_stamp) > $max_life) {
                return false;
            }
        }
        return $this->signature === $signature;
    }

    /**
    * Aliased/Implemented by Session::get
    */
    public function get($name, $default) {
        $this->touch();
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }

    /**
    * Aliased/Implemented by Session::set
    */
    public function set($name, $value) {
        $this->touch();
        $this->data[$name] = $value;
    }

    /**
    * Aliased/Implemented by Session::has
    */
    public function has($name) {
        $this->touch();
        return isset($this->data[$name]);
    }
}
