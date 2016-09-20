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

    // test bind Closure
    public function testClosureResolution()
    {
        // Actual
        $this->container->bind('name', function () {
            return 'Liu Xiang';
        });
        
        // Assert
        $this->assertEquals('Liu Xiang', $this->container->make('name'));
    }

    // test bind and bindIf
    public function testBindIfDoesntRegisteredIfServiceAlreadyRegistered()
    {
        // Actual
        $this->container->bind('name', function () {
            return 'PHP';
        });

        $this->container->bindIf('name', function () {
            return 'Laravel';
        });
        
//        $this->container->bind('name', function () {
//            return 'Container';
//        });

        // Assert
        $this->assertEquals('PHP', $this->container->make('name'));
    }

    // test Shared Closure
    public function testSharedClosureResolution()
    {
        // Arrange
        $class = new \stdClass();
        
        // Actual
        $this->container->singleton('class', function () use ($class) {
            return $class;
        });

        $this->container->bind('class2', function () use ($class) {
            return $class;
        });
        
        // Assert
        $this->assertSame($class, $this->container->make('class'));
        $this->assertSame($class, $this->container->make('class'));
        $this->assertSame($class, $this->container->make('class2'));
        $this->assertSame($class, $this->container->make('class2'));
    }

    public function testSharedConcreteResolution()
    {
        // Arrange
        $this->container->singleton(ContainerConcreteStub::class);

        $var1 = $this->container->make(ContainerConcreteStub::class);
        $var2 = $this->container->make(ContainerConcreteStub::class);

        $this->assertSame($var1, $var2);
    }

    /**
     * make
     */
    public function testAutoConcreteResolution()
    {
        // Assert
        $this->assertInstanceOf(ContainerConcreteStub::class, $this->container->make(ContainerConcreteStub::class));
    }

    public function testSlashesAreHandled()
    {
        // Actual
        $this->container->bind('\Foo', function () {
            return 'Laravel';
        });
        
        // Assert
        $this->assertSame('Laravel', $this->container->make('\Foo'));
    }

    public function testParametersCanOverrideDependencies()
    {
        // Arrange
        $stub = new ContainerDependentStub($mock = $this->createMock(IContainerStub::class));

        // Actual
        $resolved = $this->container->make(ContainerNestedDependentStub::class, [$stub]);

        // Assert
        $this->assertInstanceOf(ContainerNestedDependentStub::class, $resolved);
        $this->assertEquals($mock, $resolved->inner->stub);
    }

    public function testAbstractToConcreteResolution()
    {
        // Arrange
        $this->container->bind(IContainerStub::class, ContainerImplementationStub::class);

        // Actual
        /**
         * @var ContainerDependentStub $class
         */
        $class = $this->container->make(ContainerDependentStub::class);

        // Assert
        $this->assertInstanceOf(ContainerImplementationStub::class, $class->stub);
    }

    public function testShareMethod()
    {
        $container = new Container;
        $closure = $container->share(function () {
            return new \stdClass();
        });
        $class1 = $closure($container);
        $class2 = $closure($container);
        $this->assertSame($class1, $class2);
        $this->assertInstanceOf(\stdClass::class, $class1);
    }
}

//class ContainerConcreteStub
//{
//
//}
//
//interface IContainerStub
//{
//
//}
//
//class ContainerImplementationStub implements IContainerStub
//{
//
//}

//class ContainerImplementationStubTwo implements IContainerStub
//{
//
//}

//class ContainerDependentStub
//{
//    /**
//     * @var \MyRightCapital\Container\Tests\IContainerStub
//     */
//    public $stub;
//
//    public function __construct(IContainerStub $stub)
//    {
//        $this->stub = $stub;
//    }
//}
//
//class ContainerNestedDependentStub
//{
//    /**
//     * @var \MyRightCapital\Container\Tests\ContainerDependentStub
//     */
//    public $inner;
//
//    public function __construct(ContainerDependentStub $stub)
//    {
//        $this->inner = $stub;
//    }
//}