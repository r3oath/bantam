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

use \App\Utils\User\Session;
use \App\Utils\Misc\Config;
use \App\Utils\Data\Prelims;

/**
* Provides basic encryption, decryption, hashing and signing abilities.
* @package App\Utils\Data
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Crypto {
    const cipher_type     = MCRYPT_RIJNDAEL_256;
    const cipher_mode     = MCRYPT_MODE_CBC;
    const key_bytes_size  = 32;
    const password_algo   = PASSWORD_BCRYPT;
    const key_error       = 'Key required, pass one in as an argument, start a session or set \'bantam_app_secret_key\' in your Config.';
    const salt_error      = 'Salt required, start a session or set \'bantam_app_secret_key\' in your Config.';

    /**
    * Encrypt a string and return the base64 encoded result. If the size
    * of the data is not n * blocksize, the data will be padded with '\0'.
    * @param string $string The string to encrypt.
    * @return string The base64 encoded encrypted string.
    */
    public static function encrypt($string, $key=null) {
        if($key === null) {
            $key = Config::get('bantam_app_secret_key', null);
            if($key === null && !Session::hasBegun()) {
                throw new \ErrorException(self::key_error);
            }

            if(Session::hasBegun() && Session::valid()) {
                $key = Session::get('key');
                if($key === null) {
                    $key = openssl_random_pseudo_bytes(self::key_bytes_size);
                    Session::set('key', $key);
                }
            }
        }

        // $key = openssl_random_pseudo_bytes(self::salt_bytes_size);
        $iv_size = mcrypt_get_iv_size(self::cipher_type, self::cipher_mode);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        $encrypted_string = mcrypt_encrypt(self::cipher_type, $key, $string, self::cipher_mode, $iv);
        return base64_encode($iv.$encrypted_string);
    }

    /**
    * Decrypt a string and return the plaintext result.
    * @param string $string The string to decrypt.
    * @return string The base64 decoded decrypted string.
    */
    public static function decrypt($string, $key=null) {
        if($key === null) {
            $key = Config::get('bantam_app_secret_key', null);
            if($key === null && !Session::hasBegun()) {
                throw new \ErrorException(self::key_error);
            }

            if(Session::hasBegun() && Session::valid()) {
                $key = Session::get('key');
                if($key === null) {
                    $key = openssl_random_pseudo_bytes(self::key_bytes_size);
                    Session::set('key', $key);
                }
            }
        }

        $raw_bytes = base64_decode($string);
        $iv_size = mcrypt_get_iv_size(self::cipher_type, self::cipher_mode);
        $iv = substr($raw_bytes, 0, $iv_size);

        $encrypted_string = substr($raw_bytes, $iv_size);
        $string = mcrypt_decrypt(self::cipher_type, $key, $encrypted_string, self::cipher_mode, $iv);
        return $string;
    }

    /**
    * Sign the given string and return the same string with the signature
    * appended with a '--' seperator. This function requires you either have
    * a unique, application specific 'key' set in your Config, or that you have
    * started a Session before this function is called. If neither of these
    * conditions are met, an ErrorException will be thrown.
    * @param string $string The string to sign.
    * @return string The signed string.
    */
    public static function sign($string) {
        $salt = Config::get('bantam_app_secret_key', null);
        if($salt === null && !Session::hasBegun()) {
            throw new \ErrorException(static::salt_error);
        }

        if(Session::hasBegun() && Session::isValid()) {
            $salt = Session::get('key');
            if($salt === null) {
                $salt = openssl_random_pseudo_bytes(self::key_bytes_size);
                Session::set('key', $salt);
            }
        }

        $checksum = sha1($string.$salt);
        return $string.'--'.$checksum;
    }

    /**
    * Verify a string and signature produced by the sign function. Will return
    * true if the signature is valid and the data hasn't been tampered with,
    * will return false otherwise.
    * @param string $string The string and signature to verify.
    * @return bool True if string-signature is valid, false otherwise.
    */
    public static function verifySignature($string) {
        $array = explode('--', $string);

        if(count($array) != 2) {
            return false;
        }

        $signed_string = static::sign($array[0]);
        if($signed_string == $string) {
            return true;
        }

        return false;
    }

    /**
    * Strip the signature from a string, useful when the signature has been
    * verified and you want to work with just the original string.
    * @param string $string The string to remove the signature from.
    * @return string The string with the signature removed, or same string if no signature found.
    */
    public static function stripSignature($string) {
        $array = explode('--', $string);

        if(count($array) != 2) {
            return $string;
        }

        return $array[0];
    }

    /**
    * Hash the given string using the BCRYPT password algorithm. If you have set
    * the 'bcrypt_cost' setting in your Config, this will be used instead of the
    * default value of 10. Don't set this value to high if it causes your response
    * time to suffer, keep it high enough to stay secure, but low enough that you
    * don't annoy your legit users! (the default, 10, is usually good enough.)
    * @param string $string The string to hash.
    * @return void
    */
    public static function hash($string) {
        $cost = Config::get('bantam_bcrypt_cost', 10);
        return password_hash($string, self::password_algo, ['cost' => $cost]);
    }

    /**
    * Verify a hashed string produced by the hash function.
    * @param string $plain The plain text to verify against.
    * @param string $hash The hash to verify.
    * @return bool True if hash is valid, false otherwise.
    */
    public static function verifyHash($plain, $hash) {
        return password_verify($plain, $hash);
    }
}
