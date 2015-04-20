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
            <form action="<?php echo $action; ?>" method="<?php echo $method; ?>">
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
    * Create a form input given the name, type and if the input is flashable. Flashable
    * means the inputs previous value will be automatically inserted for you. This
    * is useful for when a form did not validate properly and need to user to correct
    * this field. This will output the relative HTML to the page.
    * @param string $name The name of the input.
    * @param string $type The type of the input, eg: text, password etc.
    * @param bool $flashable Whether this input can be flashed with old output.
    * @return void
    */
    public static function input($name, $type, $flashable=true) {
        $value = null;
        if($flashable === true) {
            $value = static::flash($name);
        }

        ?>
            <input type="<?php echo $type; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo ($value !== null) ? $value : ''; ?>">
        <?php
    }

    /**
    * Create a form textarea given the name, options which include key/value pairs
    * for 'rows' and 'cols' which default to 30 and 10 respectively. Flashable
    * means the textarea's previous value will be automatically inserted for you. This
    * is useful for when a form did not validate properly and need to user to correct
    * this field. This will output the relative HTML to the page.
    * @param string $name The name of the textarea.
    * @param array $options The array of options for 'rows' and 'cols', eg: ['rows' => 14].
    * @param bool $flashable Whether this input can be flashed with old output.
    * @return void
    */
    public static function textarea($name, $options=[], $flashable=true) {
        $value = null;
        if($flashable === true) {
            $value = static::flash($name);
        }

        $cols = (isset($options['cols'])) ? $options['cols'] : '30';
        $rows = (isset($options['rows'])) ? $options['cols'] : '10';

        ?>
            <textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="<?php echo $cols; ?>" rows="<?php echo $rows; ?>"><?php echo ($value !== null) ? $value : ''; ?></textarea>
        <?php
    }

    /**
    * Create a form select box given the name, options which include key/value pairs
    * for the relative select options (name/value), example ['QLD' => 'Queensland']. Flashable
    * means the select's previous option will be automatically selected for you. This
    * is useful for when a form did not validate properly and need to user to correct
    * this field. This will output the relative HTML to the page.
    * @param string $name The name of the select.
    * @param array $options The array of options for the select dropdown.
    * @param bool $flashable Whether this input can be flashed with old output.
    * @return void
    */
    public static function select($name, $options=[], $flashable=true) {
        $selected = null;
        if($flashable === true) {
            $selected = static::flash($name);
        }

        ?>
            <select name="<?php echo $name; ?>" id="<?php echo $name; ?>">
        <?php
            foreach ($options as $key => $value) {
                ?>
                    <option value="<?php echo $value; ?>" <?php echo ($selected !== null && $selected === $key) ? 'selected' : ''; ?>><?php echo $key; ?></option>
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
    public static function submit($name='submit', $value='Submit Form') {
        static::button($name, $value, 'submit');
    }

    /**
    * Creates a new button. This will output the relative HTML to the page.
    * @param string $name The name of the button.
    * @param string $value The value of the button, eg: 'View Options'.
    * @param string $type The type of the button, defaults to 'button'.
    * @return void
    */
    public static function button($name, $value, $type='button') {
        ?>
            <button type="<?php echo $type; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>"><?php echo $value; ?></button>
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
