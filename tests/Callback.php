<?php

namespace MyRightCapital\Container\Tests;

class Callback extends \PHPUnit_Framework_TestCase
{
    public function testFunctionCallback()
    {
        // Arrange
        $expected = 'container';
        
        // Actual
        $actual = call_user_func('MyRightCapital\Container\Tests\functionCallback', 'container'); // ($function_name, $dependency)
        
        // Assert
        $this->assertSame($expected, $actual);
    }
    
    public function testStaticClassMethodCallback()
    {
        // Arrange
        $expected = 'container';
        
        // Actual
        $actual = call_user_func('MyRightCapital\Container\Tests\StaticClassMethodCallback::staticClassMethod', 'container'); // ("$class_name::$static_method_name", $dependency)
        
        // Assert
        $this->assertSame($expected, $actual);
    }
    
    public function testClassMethodCallback()
    {
        // Arrange
        $expected = 'container';
        
        // Actual
        $actual = call_user_func([ClassMethodCallback::class, 'classMethod'], 'container'); // ([$class_name, $method_name], $dependency)
        
        // Assert
        $this->assertSame($expected, $actual);
    }
    
    public function testRelationClassMethodCallback()
    {
        // Arrange
        $expected = 'container/container';
        
        // Actual
        $actual = call_user_func([ClassMethodCallback::class, 'parent::classMethod'], 'container'); // ([$class_name, "parent::$method_name"], $dependency)
        
        // Assert
        $this->assertSame($expected, $actual);
    }
    
    public function testObjectMethodCallback()
    {
        // Arrange
        $class_method_callback = new ClassMethodCallback();
        $expected              = 'container';
        
        // Actual
        $actual = call_user_func([$class_method_callback, 'objectMethod'], 'container'); // ([$object, $method_name], $dependency)
        
        // Assert
        $this->assertSame($expected, $actual);
    }
    
    public function testClosureCallback()
    {
        // Arrange
        
        // Actual
        $actual       = call_user_func(getClosure(), 'stack', 'pipe');
        $actual_value = call_user_func($actual, 'request');
        // Assert
        $this->assertInstanceOf(\Closure::class, $actual);
        $this->assertSame('request/stack/pipe', $actual_value);
    }
}

function getClosure()
{
    return function ($stack, $pipe) {
        return function ($passable) use ($stack, $pipe) {
            return $passable . '/' . $stack . '/' . $pipe;
        };
    };
}

function functionCallback($app)
{
    return $app;
}

class StaticClassMethodCallback
{
    public static function staticClassMethod($app)
    {
        return $app;
    }
}

class ClassMethodCallback extends ParentClassMethodCallback
{
    public static function classMethod($app)
    {
        return $app;
    }
    
    public function objectMethod($app)
    {
        return $app;
    }
}

class ParentClassMethodCallback
{
    public static function classMethod($app)
    {
        return $app . '/' . $app;
    }
}