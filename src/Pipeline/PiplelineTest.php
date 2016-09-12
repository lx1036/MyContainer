<?php

interface Middleware
{
    public static function handle(Closure $closure);
}

class Pipe1 implements Middleware
{
    public static function handle(Closure $next)
    {
        // TODO: Implement handle() method.
        echo 'pipe1' . PHP_EOL;
        $next();
    }
}

class Pipe23 implements Middleware
{
    public static function handle(Closure $next)
    {
        // TODO: Implement handle() method.
        echo 'pipe2' . PHP_EOL;
        $next();
        echo 'pipe3' . PHP_EOL;
    }
}

class Pipe4 implements Middleware
{
    public static function handle(Closure $next)
    {
        // TODO: Implement handle() method.
        $next();
        echo 'pipe4' . PHP_EOL;
    }
}

function getSlice()
{
    return function ($stack, $pipe) {
        return function () use ($stack, $pipe) {
            /**
             * @var Middleware $pipe
             */
            return $pipe::handle($stack);
        };

    };
}

function then()
{
    $pipes = [
        Pipe1::class,
        Pipe23::class,
        Pipe4::class,
    ];

    $firstSlice = function () {
        echo 'pipe0' . PHP_EOL;
    };

    $pipes = array_reverse($pipes);
    $run = array_reduce($pipes, getSlice(), $firstSlice);

    $run();
}

then();

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