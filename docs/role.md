# Role
Handles user types/roles and related permissions.

## load
#### public static function load(array $arr)
Load the associative `$arr` representing roles and related permissions. The
array should follow the format `array('role' => array('perm1', 'perm2'), ...)`. Will
return `true` on success, `false` on failure.

## reset
#### public static function reset(mixed $role=null)
Resets either the given `$role` or if `$role` is set to `true` will reset
(clear) all permissions currently stored for all roles. Will return `true` on
success, `false` on failure.

## getRoles
#### public static function getRoles()
Returns an array of all the currently stored roles. This will return the roles only
and not the associated permissions for those roles. If there are no roles currently
stored, an empty array will be returned.

## getPerms
#### public static function getPerms(string $role)
Returns an array of permissions associated with the given `$role`. If the `$role`
does not exist, and empty array will be returned.

## create
#### public static function create(string $role)
Creates the specified `$role`. Will return `true` on success, `false` on failure.

## exists
#### public static function exists(string $role)
Checks if the specified `$role` exists.

## set
#### public static function set(string $role, string $perm)
Set the specified `$perm` (permission) for the given `$role`. Will return `true`
on success, `false` on failure.

## can
#### public static function can(string $role, string $perm)
Checks if the specified `$role` has (or can perform) the given `$perm` (permission).

!!! note
    The name `can` has been chosen for code readibility reasons. For example,
    checking if an admin can create posts is simply written out as
    `if(Role::can('admin', 'create_posts'))...`

## inherit
#### public static function inherit(string $role, string $from)
Adds all the roles from the role `$from` into `$role`. This is useful when creating
a superior role that needs to inherit the roles from a role below it then add its
own unique extra roles.
