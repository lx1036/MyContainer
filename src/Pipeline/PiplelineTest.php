<?php

interface Middleware
{
    public static function handle($request, Closure $closure);
}

class CheckForMaintenanceMode implements Middleware
{
    public static function handle($request, Closure $next)
    {
        echo $request . ': Check if the application is in the maintenance status.' . PHP_EOL;
        $next($request);
    }
}

class AddQueuedCookiesToResponse implements Middleware
{
    public static function handle($request, Closure $next)
    {
        $next($request);
        echo $request . ': Add queued cookies to the response.' . PHP_EOL;
    }
}

class StartSession implements Middleware
{
    public static function handle($request, Closure $next)
    {
        echo $request . ': Start session of this request.' . PHP_EOL;
        $next($request);
        echo $request . ': Close session of this response.' . PHP_EOL;
    }
}

class ShareErrorsFromSession implements Middleware
{
    public static function handle($request, Closure $next)
    {
        $next($request);
        echo $request . ': Share the errors variable from response to the views.' . PHP_EOL;
    }
}

class VerifyCsrfToken implements Middleware
{
    public static function handle($request, Closure $next)
    {
        echo $request . ': Verify csrf token when post request.' . PHP_EOL;
        $next($request);
    }
}

class Pipeline 
{
    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var int
     */
    protected $request;

    // Get the initial slice
    function getInitialSlice(Closure $destination)
    {
        return function ($passable) use ($destination) {
            return call_user_func($destination, $passable);
        };
    }
    
    // Get the slice in every step.
    function getSlice()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                /**
                 * @var Middleware $pipe
                 */
                return call_user_func_array([$pipe, 'handle'], [$passable, $stack]);
            };
        };
    }
    
    // When process the Closure, send it as parameters. Here, input an int number.
    function send(int $request)
    {
        $this->request = $request;
        return $this;
    }

    // Get the middlewares array.
    function through(array $middlewares)
    {
        $this->middlewares = $middlewares;
        return $this;
    }
    
    // Run the Filters.
    function then(Closure $destination)
    {
        $firstSlice = $this->getInitialSlice($destination);
    
        $pipes = array_reverse($this->middlewares);
        
        $run = array_reduce($pipes, $this->getSlice(), $firstSlice);
    
        return call_user_func($run, $this->request);
    }
}


/**
 * @return \Closure
 */
function dispatchToRouter()
{
    return function ($request) {
        echo $request . ': Send Request to the Kernel, and Return Response.' . PHP_EOL;
    };
}

$request = 10;

$middlewares = [
    CheckForMaintenanceMode::class,
    AddQueuedCookiesToResponse::class,
    StartSession::class,
    ShareErrorsFromSession::class,
    VerifyCsrfToken::class,
];

(new Pipeline())->send($request)->through($middlewares)->then(dispatchToRouter());

    

/**
 * 1. $stack_1
 * function () {
 *  echo 'pipe0' . PHP_EOL;
 * }
 * 1. $pipe
 * Pipe1::class
 * 1. $output
 * function () use ($stack_1, Pipe1::class) {
 *  return Pipe1::handle($stack_1);
 * }
 *
 *
 * 2. $stack_2
 * function () use ($stack_1, Pipe1::class) {
 *  return Pipe1::handle($stack_1);
 * }
 * 2. $pipe
 * Pipe2::class
 * 2. $output
 * function () use ($stack_2, Pipe2::class) {
 *  return Pipe2::handle($stack_2);
 * }
 *
 *
 * 3. $stack_3
 * function () use ($stack_2, Pipe2::class) {
 *  return Pipe2::handle($stack_2);
 * }
 * 3. $pipe
 * Pipe34::class
 * 3. $output
 * $run = function () use ($stack_3, Pipe34::class) {
 *  return Pipe34::handle($stack_3);
 * }
 *
 *
 * $run() => Pipe34::handle($stack_3) => echo 'pipe3' and $stack_3()
 * $stack_3() => $stack_2()
 * $stack_2() => Pipe1::handle($stack_1) => echo 'pipe1' and $stack_1()
 * $stack_1() => echo 'pipe0';
 *
 *
 *
 * $run() => Pipe34::handle($stack_3) => echo 'pipe3', $stack_3(), echo 'pipe4'
 * $stack_3() => Pipe2::handle($stack2) => $stack_2(), echo 'pipe2'
 * $stack_2() => Pipe1::handle($stack1) => echo 'pipe1', $stack1()
 * $stack1() => echo 'pipe0'
 *
 * so, $run() => 'pipe3', 'pipe1', 'pipe0', 'pipe2', 'pipe4'
 *
 *
 */




/*
$pipes = [
    'Pipe1',
    'Pipe2',
    'Pipe3',
    'Pipe4',
    'Pipe5',
    'Pipe6',
];

$pipes = array_reverse($pipes);

//var_dump($pipes);

/**
 * @link http://php.net/manual/zh/function.array-reduce.php
 * @param int $v
 * @param int $w
 *
 * @return int
 */
/*
function rsum($v, $w)
{
    $v += $w;
    return $v;
}

$a = [1, 2, 3, 4, 5];
$b = array_reduce($a, "rsum", 10);
echo $b . PHP_EOL;

/**
 * Laravel Pipeline Procedure
 */

/*
class TestCallUserFunc
{
    public function index($request)
    {
        echo $request . PHP_EOL;
    }
}

/**
 * @param $test
 */
/*
function testCallUserFunc($test)
{
    echo $test . PHP_EOL;
}

// [$class, $method]
call_user_func(['TestCallUserFunc', 'index'], 'pipes');
// Closure
call_user_func(function ($passable) {
    echo $passable . PHP_EOL;
}, 'pipes');
// function
call_user_func('testCallUserFunc' , 'pipes');


class TestCallUserFuncArray
{
    public function index($request)
    {
        echo $request . PHP_EOL;
    }
}

/**
 * @param $test
 */
/*
function testCallUserFuncArray($test)
{
    echo $test . PHP_EOL;
}

// [$class, $method]
call_user_func_array(['TestCallUserFuncArray', 'index'], ['pipes']);

// Closure
call_user_func_array(function ($passable) {
    echo $passable . PHP_EOL;
}, ['pipes']);

// function
call_user_func_array('testCallUserFuncArray' , ['pipes']);



//call_user_func_array();



// Decorator Pattern