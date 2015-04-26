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

class Prelim {
    public static function allowedKeys($arr, $keys=null) {
        if(!is_array($arr)) {
            return null;
        }

        if($keys !== null && !is_array($keys)) {
            return null;
        }

        if($arr === null || count($arr) < 1) {
            return null;
        }

        if($keys === null) {
            return $arr;
        }

        $new_arr = array();
        foreach ($keys as $key) {
            if(isset($arr[$key])) {
                $new_arr[$key] = $arr[$key];
            } else {
                $new_arr[$key] = null;
            }
        }

        return $new_arr;
    }

    public static function hasLength($str, $options=null) {
        if(static::strNullOrEmpty($str)) {
            return false;
        }

        if($options === null || count($options) < 1) {
            return true;
        }

        if(isset($options['min']) && strlen($str) < (int)$options['min']) {
            return false;
        }

        if(isset($options['max']) && strlen($str) > (int)$options['max']) {
            return false;
        }

        if(isset($options['exact']) && strlen($str) !== (int)$options['exact']) {
            return false;
        }

        return true;
    }

    public static function hasNulls($arr) {
        if($arr === null || !is_array($arr)) {
            return true;
        }

        if(count($arr) < 1) {
            return false;
        }

        $got_null = false;
        foreach ($arr as $val) {
            if($val === null) {
                $got_null = true;
            }
        }

        return $got_null;
    }

    public static function isNumeric($str, $options=null) {
        if($str === null || !is_numeric($str)) {
            return false;
        }

        if(isset($options['min']) && (int)$str < (int)$options['min']) {
            return false;
        }

        if(isset($options['max']) && (int)$str > (int)$options['max']) {
            return false;
        }

        if(isset($options['exact']) && (int)$str !== (int)$options['exact']) {
            return false;
        }

        return true;
    }

    public static function startsWith($str, $needle='') {
        if(static::strNullOrEmpty($str) || static::strNullOrEmpty($needle)) {
            return false;
        }

        $slice = substr($str, 0, strlen($needle));
        if(strlen($slice) != strlen($needle) || $slice !== $needle) {
            return false;
        }

        return true;
    }

    public static function endsWith($str, $needle='') {
        if(static::strNullOrEmpty($str) || static::strNullOrEmpty($needle)) {
            return false;
        }

        $slice = substr($str, -strlen($needle));
        if(strlen($slice) != strlen($needle) || $slice !== $needle) {
            return false;
        }

        return true;
    }

    public static function strNullOrEmpty($str) {
        return ($str === null || !is_string($str) || strlen($str) < 1);
    }

    public static function clamp($val, $min=null, $max=null) {
        if($val === null || !is_numeric($val)) {
            return null;
        }

        if($min === null || $max === null || !is_numeric($min) || !is_numeric($max)) {
            return $val;
        }

        return ($val < $min) ? $min : (($val > $max) ? $max : $val);
    }
}
