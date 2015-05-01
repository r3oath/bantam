# Validate
General data & user input validation.

## url
#### public static function url(string $url, array $protos=array('http', 'https'), bool $dns=false)
Validates the given `$url` is syntactically correct, matches the given protocols `$protos`
and optionally passes a host name DNS check (making sure the url is live). If
`$protos` is empty or `$dns` is set to anything other that `true` or `false`, any
`$url` given will fail the check. URL's can be in a variety of formats, such as
`http://www.github.com`, `http://github.com` or `github.com` for example. In the last
example `github.com`, the first available protocol in `$protos` will be use for the
DNS check if `$dns` is set to true. Will return `true` if valid, `false` otherwise.

## ip
#### public static function ip(string $ip)
Validates the given IP Address `$ip` is syntactically correct. Any syntactically
valid IP Addresses provided that fall into private or reserved ranges will fail
validation. Will return `true` if valid, `false` otherwise.

## email
#### public static function email(string $email)
Validates the given `$email` address, however this will not check that the email
address has been registered or is useable. Will return `true` if valid, `false`
otherwise.

## bool
#### public static function bool(mixed $bool)
Validates the given argument `$bool` is a representation of true or false. This includes
values such as `0`, `1`, `true`, `false`, `yes`, `no` etc. Will return `true` if
valid, `false` otherwise.

## age
#### public static function age(mixed $age)
Validates the given `$age` is a numeric representation that falls within the range
of 1 and 130. Will return `true` if valid, `false` otherwise.

## int
#### public static function int(mixed $int)
Validates the given argument `$int` is a valid representation of an interger. This
includes other formats such as hex and octal. Will return `true` if valid, `false`
otherwise.

## float
#### public static function float(mixed $float)
Validates the given argument `$float` is a valid floating point number. The thousands
seperator is permitted and will validate, so values such as `1,300.50` are considered
valid. Will return `true` if valid, `false` otherwise.

## regex
#### public static function regex(string $exp, string $str)
Runs the given `$str` against the regular expression `$exp`. Will return `true`
if valid, `false` otherwise.
