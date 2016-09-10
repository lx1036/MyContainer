<?php

namespace MyRightCapital\Container\Tests;

use MyRightCapital\Container\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Container $container
     */
    protected $container;

    public function setUp()
    {
        $this->container = new Container();
    }

    public function testGlobalInstance()
    {
        Container::setInstance($this->container);
        $this->assertSame($this->container, Container::getInstance());
        Container::setInstance(new Container());
        $container2 = Container::getInstance();
        $this->assertInstanceOf(Container::class, $container2);
        $this->assertNotSame($this->container, $container2);
    }

    public function testClosureResolution()
    {
        $this->container->bind('name', function () {
            return 'Liu Xiang';
        });
        
        $this->assertEquals('Liu Xiang', $this->container->make('name'));
    }
}