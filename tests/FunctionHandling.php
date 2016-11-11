<?php

namespace MyRightCapital\Container\Tests;

class FunctionHandling extends \PHPUnit_Framework_TestCase
{
    public function testCallUserFunc()
    {
        // Arrange
        $provider = new Provider();
        $app      = new Application($provider);

        // Actual
        $actual = call_user_func('MyRightCapital\Container\Tests\callUserFunc', $app);

        // Assert
        $this->assertSame('This is a service provider.', $actual);
    }

    public function testCallUserFuncArray()
    {
        // Arrange
        $provider = new Provider();
        $app      = new Application($provider);

        // Actual
        $actual = call_user_func_array('MyRightCapital\Container\Tests\callUserFunc', [$app]);

        // Assert
        $this->assertSame('This is a service provider.', $actual);
    }

    public function testFuncArgs()
    {
        // Arrange
        $provider = new Provider();
        $app      = new Application($provider);
        
        // Actual
        $arg_number0 = $app->testFuncArg(); 
        $arg_number1 = $app->testFuncArg('Laravel');
        $arg_number2 = $app->testFuncArg(['Laravel', 'PHP']);
        
        // Assert
        $this->assertSame(0, $arg_number0);
        $this->assertSame(1, $arg_number1);
        $this->assertSame(2, $arg_number2);
    }

    public function testFunctionExists()
    {
        // Arrange
        $expected = 'Container';

        // Actual
        $actual = functionExists('Container');

        // Assert
        $this->assertSame($expected, $actual);
    }

    public function testRegisterShutdownFunction()
    {
        // Arrange
        $provider = new Provider();
        $app      = new Application($provider);
        
        // Actual
        $actual = $app->testRegisterShutdownFunction();
        
        // Assert
        $this->assertSame('Shutdown the application.', $actual);
    }
}

if (!function_exists('functionExists')) {
    function functionExists($container)
    {
        return $container;
    }
}

function callUserFunc($app)
{
    return $app->register();
}

class Application
{
    private $provider;

    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    public function register()
    {
        return $this->provider->register();
    }

    public function testFuncArg()
    {
        if (func_num_args() > 0) {
            $patterns = is_array(func_get_arg(0)) ? func_get_arg(0) :func_get_args();
            return count($patterns);
        }
        
        return 0;
    }

    public function testRegisterShutdownFunction()
    {
//        return register_shutdown_function([$this, 'shutdown'], 'Shutdown the application.');
        return register_shutdown_function([$this, 'shutdown']);
//        exit();
    }

    public function shutdown($shutdown)
    {
        return 'Shutdown the application.';
//        return $shutdown;
    }
}

class Provider
{
    public function register()
    {
        return 'This is a service provider.';
    }
}
