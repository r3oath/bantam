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

use \App\Utils\Http\Request;
use \App\Utils\Http\Csrf;
use \App\Utils\User\Session;
use \App\Utils\Data\Prelims;

/**
* Generates HTML forms.
* @package App\Utils\Input
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Form {
    /**
    * Start a new form, using the given method and action. Includes a hidden
    * field with the CSRF token embedded. Default method is post, and action is self.
    * This will output the relative HTML to the page.
    * @param string $method The form method, either post or get.
    * @param string $action The form action, aka a URL/URI.
    * @return void
    */
    public static function start($method='post', $action='') {
        $request_type = $method;
        $method = ($method == 'post' || $method == 'put' || $method == 'delete') ? 'post' : 'get';

        ?>
            <form action="<?php echo $action; ?>" method="<?php echo $method; ?>" enctype="multipart/form-data">
            <input type="hidden" name="_request" value="<?php echo $request_type; ?>">
        <?php

        if(Session::hasBegun()) :
            $csrf_token = Csrf::generate();
            Session::set('csrf_token', $csrf_token);

            ?>
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <?php
        endif;
    }

    /**
    * Ends a form with the relative closing tag. This will output the relative
    * HTML to the page.
    * @return void
    */
    public static function end() {
        ?>
            </form>
        <?php
    }

    /**
    * Create a form input given the name and options. In the options, flashable
    * means the inputs previous value will be automatically inserted for you. This
    * is useful for when a form did not validate properly and need the user to correct
    * this field. This will output the relative HTML to the page.
    * Options include 'flashable' => [true|false], 'type' => [string(default='text')],
    * 'class' => [string]
    * @param string $name The name of the input.
    * @param array $options The options to pass in as an associative array.
    * @return void
    */
    public static function input($name, $options=[]) {
        $value = null;
        if(isset($options['flashable']) && $options['flashable'] === true) {
            $value = static::flash($name);
        }

        $type = isset($options['type']) ? $options['type'] : 'text';
        $class = isset($options['class']) ? $options['class'] : '';

        ?>
            <input type="<?php echo $type; ?>" class="<?php echo $class; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo ($value !== null) ? $value : ''; ?>">
        <?php
    }

    /**
    * Create a file upload input given the name and options. This will output the
    * relative HTML to the page.
    * @param string $name The name of the input.
    * @param array $options The options to pass in as an associative array.
    * @return void.
    */
    public static function file($name, $options=[]) {
        $options['type'] = 'file';
        $options['flashable'] = false;
        static::input($name, $options);
    }

    /**
    * Create a form textarea given the name and options, in the options flashable
    * means the textarea's previous value will be automatically inserted for you. This
    * is useful for when a form did not validate properly and need to user to correct
    * this field. This will output the relative HTML to the page. Options include:
    * 'cols' => [string(default='30')], 'rows' => [string(default='10')], 'flashable'
    * => [true|false], 'class' => [string]
    * @param string $name The name of the textarea.
    * @param array $options The options to pass in as an associative array.
    * @return void
    */
    public static function textarea($name, $options=[]) {
        $value = null;
        if(isset($options['flashable']) && $options['flashable'] === true) {
            $value = static::flash($name);
        }

        $cols = isset($options['cols']) ? $options['cols'] : '30';
        $rows = isset($options['rows']) ? $options['rows'] : '10';
        $class = isset($options['class']) ? $options['class'] : '';

        ?>
            <textarea name="<?php echo $name; ?>" class="<?php echo $class; ?>" id="<?php echo $name; ?>" cols="<?php echo $cols; ?>" rows="<?php echo $rows; ?>"><?php echo ($value !== null) ? $value : ''; ?></textarea>
        <?php
    }

    /**
    * Create a form select box given the name, children which include key/value pairs
    * for the relative select options (name/value), example ['QLD' => 'Queensland']. In the options,
    * flashable means the select's previous option will be automatically selected for you. This
    * is useful for when a form did not validate properly and need to user to correct
    * this field. This will output the relative HTML to the page. Options include
    * 'class' => [string], 'class_children' => [string], 'class_selected' => [string],
    * 'flashable' => [true|false].
    * @param string $name The name of the select.
    * @param array $children The array of options for the select dropdown.
    * @return void
    */
    public static function select($name, $children, $options=[]) {
        $selected = null;
        if(isset($options['flashable']) && $options['flashable'] === true) {
            $selected = static::flash($name);
        }

        $class = isset($options['class']) ? $options['class'] : '';
        $class_children = isset($options['class_children']) ? $options['class_children'] : '';
        $class_selected = isset($options['class_selected']) ? $options['class_selected'] : '';

        ?>
            <select name="<?php echo $name; ?>" class="<?php echo $class; ?>" id="<?php echo $name; ?>">
        <?php
            foreach ($children as $key => $value) {
                ?>
                    <option value="<?php echo $value; ?>" class="<?php echo ($selected !== null && $selected === $value) ? $class_children.' '.$class_selected : $class_children; ?>" <?php echo ($selected !== null && $selected === $value) ? 'selected' : ''; ?>><?php echo $value; ?></option>
                <?php
            }
        ?>
            </select>
        <?php
    }

    /**
    * Creates a new submit button. This will output the relative HTML to the page.
    * @param string $name The name of the submit button.
    * @param string $value The value of the submit button, eg: 'Submit Comment'.
    * @return void
    */
    public static function submit($name='submit', $options=[]) {
        $options['type'] = 'submit';
        static::button($name, $options);
    }

    /**
    * Creates a new button. This will output the relative HTML to the page.
    * @param string $name The name of the button.
    * @param string $value The value of the button, eg: 'View Options'.
    * @param string $type The type of the button, defaults to 'button'.
    * @return void
    */
    public static function button($name, $options=[]) {
        $type = isset($options['type']) ? $options['type'] : 'button';
        $value = isset($options['value']) ? $options['value'] : 'Submit';
        $class = isset($options['class']) ? $options['class'] : '';

        ?>
            <button type="<?php echo $type; ?>" name="<?php echo $name; ?>" class="<?php echo $class; ?>" id="<?php echo $name; ?>"><?php echo $value; ?></button>
        <?php
    }

    /**
    * Checks whether the form with the specified submit button name or field
    * was submitted, returns true if it was, false otherwise.
    * @param string $name The name of the field to look for.
    * @return bool True if field was found (form submitted), false otherwise.
    */
    public static function submitted($name='submit') {
        $params = Request::allowedParams([$name]);
        $param = isset($params[$name]) ? $params[$name] : null;
        return $param !== null;
    }

    /**
    * Checks to see if the CSRF token specified in the form matches the one
    * stored in the users session, assuming a session has been started.
    * @return True if no session started or CSRF tokens match, false otherwise.
    * @return void
    */
    public static function verifyCsrf() {
        if(Session::hasBegun()) {
            if(!Session::valid()) {
                return false;
            }

            $params = Request::allowedParams(['csrf_token']);
            if(Prelims::hasNulls($params)) {
                return false;
            }

            $csrf_token = isset($params['csrf_token']) ? $params['csrf_token'] : null;

            // At this point, if true, we have a csrf_token and it matches
            // the one stored in the users session.
            if($csrf_token !== null && $csrf_token === Session::get('csrf_token')) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
    * Gets the specified form fields value based on its name.
    * @param string $name The name of the field to look for.
    * @return mixed The value of the field or null if not found.
    */
    public static function flash($name) {
        $param = Request::allowedParams([$name]);
        return isset($param[$name]) ? $param[$name] : null;
    }
}
