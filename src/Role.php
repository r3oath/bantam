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

class Role {
    private static $roles = array();

    public static function create($role) {
        if(Prelim::strNullOrEmpty($role)) {
            return false;
        }

        if(!isset(static::$roles[$role])) {
            static::$roles[$role] = array();
        }

        return true;
    }

    public static function exists($role) {
        if(Prelim::strNullOrEmpty($role)) {
            return false;
        }

        if(isset(static::$roles[$role])) {
            return true;
        }

        return false;
    }

    public static function set($role, $perm) {
        if(!static::exists($role)) {
            return false;
        }

        if(Prelim::strNullOrEmpty($perm)) {
            return false;
        }

        static::$roles[$role][] = $perm;
        return true;
    }

    public static function can($role, $perm) {
        if(!static::exists($role)) {
            return false;
        }

        if(Prelim::strNullOrEmpty($perm)) {
            return false;
        }

        if(in_array($perm, static::$roles[$role])) {
            return true;
        }

        return false;
    }

    public static function inherit($role, $from) {
        if(!static::exists($role) || !static::exists($from)) {
            return false;
        }

        foreach (static::$roles[$from] as $perm) {
            static::set($role, $perm);
        }

        return true;
    }
}
