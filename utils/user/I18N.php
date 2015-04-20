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

use \App\Utils\Data\Assets;
use \App\Utils\Misc\Config;

/**
* Manages internationalization (i18n).
* @package App\Utils\User
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class I18N {
    /**
    * @var array An array of locales and the respective files they are located in.
    */
    private static $translations = array();

    /**
    * @var array The table holding the pack.token keys and the respective localized strings.
    */
    private static $table = array();

    /**
    * @var string The currently selected locale.
    */
    private static $locale = 'en';

    /**
    * Load an associative array of locales and file locations. For example,
    * load(['en_au' = > 'en_au.php', 'en_us' => 'en_us.php'])
    * @param array $files an associative array of locales and file locations.
    * @return void.
    */
    public static function load($files) {
        static::$translations = $files;
    }

    /**
    * Load in locales from the specified folder. Locale names will be parsed from
    * the files residing in the folder, example, a file such as en_us.php will
    * be registered as the locale 'en_us'.
    * @param string $folder The folder containing locale files.
    * @return void.
    */
    public static function folder($folder) {
        $folder = trim($folder, '/');
        if(file_exists($folder)) {
            $contents = scandir($folder);
            if(count($contents) > 2) {
                // Get only files in this directory, not '.' and '..'.
                unset($contents[0]);
                unset($contents[1]);
                $contents = array_values($contents);

                // Find only files that end with .php
                foreach ($contents as $key => $value) {
                    if(strrpos($value, '.php') !== false) {
                        $parts = explode('.', $value);
                        if(count($parts) > 1) {
                            $locale = $parts[0];
                            static::$translations += [$locale => $folder.'/'.$value];
                        }
                    }
                }
            }
        }
    }

    /**
    * Set the current locale to the specified type and load the translations,
    * if not found, load the default from Config, if neither found, returns false.
    * @param string $name The name of the locale file to load.
    * @return mixed Void if load successful, false otherwise.
    */
    public static function locale($name) {
        $locale = $name;
        $default = Config::get('bantam_default_locale', 'en');

        if(isset(static::$translations[$locale])) {
            static::$table = Assets::load(static::$translations[$locale]);
        } else if(isset(static::$translations[$default])) {
            static::$table = Assets::load(static::$translations[$default]);
        } else {
            return false;
        }
    }

    /**
    * Gets the currently set locale.
    * @return string The currently set locale.
    */
    public static function getLocale() {
        return static::$locale;
    }

    /**
    * Echo a translation out to the page given the pack name and the token, example
    * e('users', 'login_notice').
    * @param string $pack_token The pack name and the token.
    * @return void.
    */
    public static function e($pack_token, $replacements=[]) {
        echo static::r($pack_token, $replacements);
    }

    /**
    * Return a translation given the pack name and the token, example
    * r('users.login_notice').
    * @param string $pack_token The pack name and the token.
    * @param array $replacements An array of values to replace in the localized string denoted by {0..n} placeholders.
    * @return string The translation or an empty string if not found.
    */
    public static function r($pack_token, $replacements=[]) {
        $parts = explode('.', $pack_token);
        if(count($parts) > 1) {
            $pack  = $parts[0];
            $token = $parts[1];
            if(isset(static::$table[$pack][$token])) {
                $string = static::$table[$pack][$token];
                if(count($replacements) > 0) {
                    for ($i = 0; $i < count($replacements); $i++) {
                        $string = str_replace("{{$i}}", $replacements[$i], $string);
                    }
                    return $string;
                } else {
                    return $string;
                }
            }
        }

        return '';
    }
}
