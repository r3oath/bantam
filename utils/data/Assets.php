<?php namespace App\Utils\Data;

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

use \App\Utils\Data\Prelims;

/**
* Assists in the management of web assets, such as server-side includes, css files,
* JavaScript files etc.
* <code>
* // Loading a server-side include.
* Assets::load('includes/user-portal.php');
*
* // Placing the results of an assest into a variable or passing it to a function.
* Config::create(Assets::load('app/config.php'));
*
* // Loading a CSS file in your HTML
* <?php Assets::css('css/bootstrap.css'); ?>
* </code>
* @package App\Utils\Data
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Assets {
    /**
    * Load (php require) the given asset, retuns the results of the file.
    * @param string $name The location/name of the asset to load.
    * @return mixed The returned results of the file.
    */
    public static function load($name) {
        if(file_exists($name)) {
            return require($name);
        }

        return null;
    }

    /**
    * Creates an HTML css link entry given the asset name.
    * @param string $name The name of the asset.
    * @return void.
    */
    public static function css($name) {
        ?>
            <link rel="stylesheet" href="<?php echo $name; ?>">
        <?php
    }

    /**
    * Creates an HTML js script entry given the asset name.
    * @param string $name The name of the asset.
    * @return void.
    */
    public static function script($name) {
        ?>
            <script src="<?php echo $name; ?>"></script>
        <?php
    }

    /**
    * Recursively traverses the given folder and loads all files that end with
    * the given allowed extensions, defaults to ['.php'].
    * @param string $item The directory to scan.
    * @param array $allowed_extensions An array of allowed filed extensions.
    * @return void.
    */
    public static function folder($item, $allowed_extensions=['.php']) {
        // If this item is a directory, scan it.
        if(is_dir($item)) {
            $items = scandir($item);
            foreach ($items as $key) {
                if($key == '.' || $key == '..') {
                    continue;
                }

                // Recursively load each directory as we find them.
                static::folder($item.'/'.$key, $allowed_extensions);
            }

            return;
        }

        // If this item is a file, attempt to load it.
        if(is_file($item)) {
            $ok = false;
            foreach ($allowed_extensions as $ext) {
                if(Prelims::endsWith($item, $ext)) {
                    $ok = true;
                    break;
                }
            }

            // If file extension matched one listed in the allowed
            // extensions, load it up.
            if($ok === true) {
                static::load($item);
            }
        }
    }
}
