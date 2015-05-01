# Prelim
Handles preliminary data processing and validation.

## allowedKeys
#### public static function allowedKeys(array $arr, array $keys=null)
Takes the `$arr` array and returns a copy of it with all key/value pairs removed
that don't match the keys specified in the `$keys` array. If `$keys` is not
specified, it will simply return the `$arr` array.

!!! hint
    This is useful when grabbing just the `$_POST` or `$_GET` values you want
    after a form submission. For example `$my_vars = Prelim::allowedKeys($_POST, ['username', 'password'])`

## hasLength
#### public static function hasLength(string $str, array $options=null)
Checks that the specified `$str` is not null or empty. If the associative
array `$options` is specified, the string can be checked against
`min`, `max`, or `exact` values.

## hasNulls
#### public static function hasNulls(array $arr)
Check that the specified array `$arr` doesn't contain any null values.

!!! hint
    Following on from the `allowedKeys` example, this can be used to verify that
    you got the `$_POST` or `$_GET` variables you require. For example
    `if(!Prelim::hasNulls($my_vars))...`

## isNumeric
#### public static function isNumeric(string $str, array $options=null)
Checks that the specified `$str` is numeric. If the associative
array `$options` is specified, the numeric string can be checked against
`min`, `max`, or `exact` values.

## startsWith
#### public static function startsWith(string $str, string $needle='')
Checks the specified `$str` starts with the given `$needle`.

## endsWith
#### public static function endsWith(string $str, string $needle='')
Checks the specified `$str` ends with the given `$needle`.

## strNullOrEmpty
#### public static function strNullOrEmpty(string $str)
Checks the specified `$str` is a string and is not null or empty. For example
`Prelim::strNullOrEmpty(123)` would return false, where as `Prelim::strNullOrEmpty('123')`
would return true.

## clamp
#### public static function clamp(numeric $val, numeric $min=null, numeric $max=null)
Clamps the specified `$val` between the given values `$min`
and `$max`. If `$min` and/or `$max` are not specified the original value of
 `$val` will be returned.
