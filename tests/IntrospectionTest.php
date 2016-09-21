<?php

namespace MyRightCapital\Container\Tests;

class IntrospectionTest extends \PHPUnit_Framework_TestCase
{
    public function testClassExists()
    {
        // Arrange
        
        // Actual
        $class_exists = class_exists(TestClassExists::class);
        // Assert
        $this->assertTrue($class_exists);
    }
}

class TestClassExists
{
    
}