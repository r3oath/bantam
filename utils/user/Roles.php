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

/**
* Manages user roles and permissions associated with those roles.
* <code>
* // Create two user types.
* Roles::add('admin')->will('create_users', 'delete_users', 'edit_users');
* Roles::add('user')->will('view_posts');
*
* // Check if a user has the given permission.
* if(Roles::can('admin', 'delete_users')) {
*     delete_user($id);
* }
* </code>
* @package App\Utils\User
* @author Tristan Strathearn <r3oath@gmail.com>
* @license http://opensource.org/licenses/MIT MIT License (MIT)
*/
class Roles {
    /**
    * @var array An array of all registered roles and the respective persmissions.
    */
    private static $role_table = array();

    /**
    * @var string This roles name.
    */
    private $role_name;

    /**
    * Creates a new chainable role specifying the existing role name.
    * @param string $name The name of the role to chain.
    * @return void.
    */
    private function __construct($name) {
        $this->role_name = $name;
    }

    /**
    * Adds a new permission to the current role.
    * @param string $permission The new permission to add.
    * @return Roles A reference to the current role.
    */
    public function will($permission) {
        static::addPermission($this->role_name, $permission);
        return $this;
    }

    /**
    * Adds all the permissions from the specified role to the current role. Optionally
    * specifying permissions to exclude.
    * @param string $role The role to pull permissions from.
    * @param array $exclude Optional array of permissions to exclude when inheriting.
    * @return Roles A reference to the current role.
    */
    public function inherits($role, $exclude=[]) {
        if(!isset(static::$role_table[$role])) {
            return $this;
        }

        foreach (static::$role_table[$role] as $key => $permission) {
            if(!in_array($permission, $exclude)) {
                $this->will($permission);
            }
        }

        return $this;
    }

    /**
    * Load in an associative array of roles and permissions. Handy for pulling in
    * roles etc from a config file instead of adding each role/permission manually.
    * @param array $roles_array The associative array of roles and corresponding permissions.
    * @return void.
    */
    public static function load($roles_array) {
        foreach ($roles_array as $role => $permissions) {
            static::add($role);

            foreach ($permissions as $key => $value) {
                static::addPermission($role, $value);
            }
        }
    }

    /**
    * Add a new role.
    * @param string $name The name of the new role.
    * @return Roles A reference to the newly created/existing role.
    */
    public static function add($name) {
        if(isset(static::$role_table[$name])) {
            return new Roles($name);
        }

        static::$role_table[$name] = array();
        return new Roles($name);
    }

    /**
    * Add a permission to the specified role.
    * @param string $role_name The name of the role to add the permission to.
    * @param string $permission The name of the new permission to add.
    * @return bool True if the permission was added, false if the role doesn exist or the permission already exists.
    */
    public static function addPermission($role_name, $permission) {
        if(!isset(static::$role_table[$role_name])) {
            return false;
        }

        if(in_array($permission, static::$role_table[$role_name])) {
            return false;
        }

        static::$role_table[$role_name][] = $permission;
        return true;
    }

    /**
    * Checks if the specified role has a particular permission. Named to be easily
    * readable in code. Example can('admin', 'create_posts'). "Can the admin create posts?".
    * @param string $role The name of the role to check permission against.
    * @param string $permission The name of the permission to check for.
    * @return bool True if the role has the permission, false otherwise.
    */
    public static function can($role, $permission) {
        if(!isset(static::$role_table[$role])) {
            return false;
        }

        if(in_array($permission, static::$role_table[$role])) {
            return true;
        }

        return false;
    }
}
