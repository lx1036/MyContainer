<?php

namespace MyRightCapital\Container\Tests;

use MyRightCapital\Container\Container;

class ContainerBindTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Container $container
     */
    protected $container;

    public function setUp()
    {
        $this->container = new Container();
    }

    public function testBindClosure()
    {
        // Arrange
        $expected = 'Laravel is a PHP Framework.';
        $this->container->bind('PHP', function () use ($expected) {
            return $expected;
        });

        // Actual
        $actual = $this->container->make('PHP');

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testBindInterfaceToImplement()
    {
        // Arrange
        $this->container->bind(IContainerStub::class, ContainerImplementationStub::class);

        // Actual
        $actual = $this->container->make(IContainerStub::class);

        // Assert
        $this->assertInstanceOf(IContainerStub::class, $actual);
    }

    public function testBindDependencyResolution()
    {
        // Arrange
        $this->container->bind(IContainerStub::class, ContainerImplementationStub::class);

        // Actual
        $actual = $this->container->make(ContainerNestedDependentStub::class);

        // Assert
        $this->assertInstanceOf(ContainerDependentStub::class, $actual->containerDependentStub);
        $this->assertInstanceOf(ContainerImplementationStub::class, $actual->containerDependentStub->containerStub);
    }

    public function testSingleton()
    {
        // Arrange
        $this->container->singleton(ContainerConcreteStub::class);
        $expected = $this->container->make(ContainerConcreteStub::class);

        // Actual
        $actual = $this->container->make(ContainerConcreteStub::class);

        // Assert
        $this->assertSame($expected, $actual);
    }

    public function testInstanceExistingObject()
    {
        // Arrange
        $expected = new ContainerImplementationStub();
        $this->container->instance(IContainerStub::class, $expected);

        // Actual
        $actual = $this->container->make(IContainerStub::class);

        // Assert
        $this->assertSame($expected, $actual);
    }
}

class ContainerConcreteStub
{

}

interface IContainerStub
{

}

class ContainerImplementationStub implements IContainerStub
{

}

class ContainerDependentStub
{
    /**
     * @var \MyRightCapital\Container\Tests\IContainerStub
     */
    public $containerStub;

    public function __construct(IContainerStub $containerStub)
    {
        $this->containerStub = $containerStub;
    }
}

class ContainerNestedDependentStub
{
    /**
     * @var \MyRightCapital\Container\Tests\ContainerDependentStub
     */
    public $containerDependentStub;

    public function __construct(ContainerDependentStub $containerDependentStub)
    {
        $this->containerDependentStub = $containerDependentStub;
    }
}