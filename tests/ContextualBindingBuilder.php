<?php

namespace MyRightCapital\Container\Tests;

use MyRightCapital\Container\Container;

class ContextualBindingBuilder extends \PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = new Container();
    }

    public function testHelp()
    {
        $this->assertTrue(true);
    }
}
