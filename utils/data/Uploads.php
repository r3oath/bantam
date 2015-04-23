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

use \App\Utils\Misc\Config;
use \App\Utils\Data\Prelims;

/**
* Handles the uploading of files and data to the server.
* <code>
* // Our example image upload form was submitted...
* if(Form::submitted()) {
*     // Options: If any files already exist, give them a new unique name.
*     $results = Uploads::walk('storage', ['unique' => true]);
*     if(Uploads::failed($results, 'image_field')) {
*         echo Uploads::error($results, 'image_field');
*     } else {
*         // Newly uploaded file is now available at...
*         // eg: storage/0_image.jpg
*         echo Uploads::path($results, 'image_field');
*     }
* }
* </code>
* @package App\Utils\Data
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Uploads {
    /**
    * Processes and handles a single file upload to the server. Numerous options
    * can be passed in a associative array to customize the upload process. These include:
    * [
    *     'ignore_ext' => [true|false] // Ignores the file extension check.
    *     'ignore_size' => [true|false] // Ignores the file size check.
    *     'overwrite' => [true|false] // If true, will overwrite existing files with the same name.
    *     'unique' => [true|false] // If true, will generate a unique name for the file if one with the same name already exists.
    * ]
    * @param array $file The $_FILES['xyz'] entry to process.
    * @param string $uploads_dir The directory to store the newly uploaded file.
    * @param array $options Various options to use while processing.
    * @return array An array of file statistics or an 'error' key with a message if one occured.
    */
    public static function handle($file, $uploads_dir, $options=[]) {
        $file_name = basename($file['name']);
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        // Clean up the file name for storage.
        $clean_name = strtolower(preg_replace('/([^a-zA-Z0-9_\-\.]+)/', '_',
                        substr($file_name, 0, -strlen($file_ext)))).$file_ext;
        $target = rtrim($uploads_dir, '/').'/'.$clean_name;
        $file_size = $file['size'];

        if($file['error'] !== UPLOAD_ERR_OK) {
            return array('error' => static::errorToMessage($file['error']));
        }

        // File extension check.
        if(!isset($options['ignore_ext']) || $options['ignore_ext'] == false) {
            $allowed_ext = Prelims::cleanList(Config::get('bantam_allowed_file_exts', 'jpg, jpeg, png, gif'), ',');
            if(!in_array($file_ext, $allowed_ext)) {
                return array('error' => 'File extension not permitted.');
            }
        }

        // File size check.
        if(!isset($options['ignore_size']) || $options['ignore_size'] == false) {
            $max_file_size = Config::get('bantam_max_file_size', 2097152);
            if($file_size > $max_file_size) {
                return array('error' => 'File size exceeds limit.');
            }
        }

        // Existing file check.
        if((!isset($options['overwrite']) || $options['overwrite'] == false) &&
           (!isset($options['unique']) || $options['unique'] == false)) {
            if(file_exists($target)) {
                return array('error' => 'File already exists at the given location.');
            }
        }

        // Overwrite is enabled, delete existing file if it exists or generate new name.
        if(!isset($options['unique']) || $options['unique'] == false) {
            if(file_exists($target)) {
                unlink($target);
            }
        } else {
            $num = 0;
            while(true) {
                if(!file_exists(rtrim($uploads_dir, '/').'/'.$num.'_'.$clean_name)) {
                    $target = rtrim($uploads_dir, '/').'/'.$num.'_'.$clean_name;
                    break;
                }

                $num += 1;
            }
        }

        // Attempt to move the uploaded file to the storage container.
        $err = move_uploaded_file($file['tmp_name'], $target);
        if($err === false) {
            return array('error' => 'Error occured while moving uploaded file to destination.');
        }

        // Return the stored file information.
        return array(
            'name' => $file_name,
            'ext' => $file_ext,
            'size' => $file_size,
            'path' => $target
        );
    }

    /**
    * Converts the PHP file upload ENUM error code to a human readable string.
    * @param enum $code The PHP file upload error ENUM.
    * @return string The message code in human readable format.
    */
    private static function errorToMessage($code) {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                return "The uploaded file exceeds the upload_max_filesize directive in php.ini";
            case UPLOAD_ERR_FORM_SIZE:
                return "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
            case UPLOAD_ERR_PARTIAL:
                return "The uploaded file was only partially uploaded";
            case UPLOAD_ERR_NO_FILE:
                return "No file was uploaded";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Missing a temporary folder";
            case UPLOAD_ERR_CANT_WRITE:
                return "Failed to write file to disk";
            case UPLOAD_ERR_EXTENSION:
                return "File upload stopped by extension";
            default:
                return "Unknown upload error";
        }
    }

    /**
    * Walks the $_FILES array and handles each file entry as a seperate upload.
    * @param string $uploads_dir The directory to store the newly uploaded file.
    * @param array $options Various options to use while processing (see the docs for Uploads::handle()).
    * @return array An associative array of each files input name field and its upload status (see the docs for Uploads::handle()).
    */
    public static function walk($uploads_dir, $options=[]) {
        $results = array();
        foreach ($_FILES as $name => $file) {
            $results[$name] = static::handle($file, $uploads_dir, $options);
        }

        return $results;
    }

    /**
    * Returns true if the given field in the results has encountered an error.
    * @param array $results The results of a Uploads::walk(...).
    * @param string $token The file input name to check against.
    * @return bool True if an error occured, false otherwise.
    */
    public static function failed($results, $token) {
        return isset($results[$token]['error']);
    }

    /**
    * Gets the error message associated with the given input field name in the results.
    * @param array $results The results of a Uploads::walk(...).
    * @param string $token The file input name to return the error message for.
    * @return string The error message string.
    */
    public static function error($results, $token) {
        return static::failed($results, $token) ? $results[$token]['error'] : '';
    }

    /**
    * Returns the local path for the newly uploaded file given its input field name.
    * @param array $results The results of a Uploads::walk(...).
    * @param string $token The file input name to return the local path for.
    * @return string The local file path.
    */
    public static function path($results, $token) {
        return !static::failed($results, $token) ? $results[$token]['path'] : '';
    }
}
