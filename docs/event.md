# Event
Allows the system to register functions to event names which are
then later executed when the event is fired.

## register
#### public static function register(string $name, callable $fn)
Register a new function `$fn` for the given event `$name`. Will return `true` on success,
`false` otherwise. The function provided can either be a `Closure` or a `callable`.

!!! note
    When registering a new function, the `Closure` method is generally cleaner looking
    and shows the binding of the function to the event. For example,
    `Event::register('load', function(){ echo 'Hello World!'; });`

## fire
#### public static function fire(string $name, mixed $data=null)
Fires the given event `$name` and executes all associated functions. If provided,
each function will be passed `$data`. This may be used for any purpose and `$data`
can be of any type. Will return `false` if the event failed to fire, otherwise it
will return the number of associated functions that were run.

!!! note
    As this function can return `0` if the event was successful but no associated
    functions were registered, to check for failure it is advised to use the
    `===` comparison.
