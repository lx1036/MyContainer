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
    
    public function testInterfaceExists()
    {
        // Arrange
        
        // Actual
        $interface_exists = interface_exists(TestInterfaceExists::class);
        // Assert
        $this->assertTrue($interface_exists);
    }
    
    public function testMethodExists()
    {
        // Arrange
        $test_class_exists = new TestClassExists();
        
        // Actual
        $object_method_exists1    = method_exists($test_class_exists, 'testPrivateMethodExists');
        $object_method_exists2    = method_exists($test_class_exists, 'testProtectedMethodExists');
        $object_method_exists3    = method_exists($test_class_exists, 'testPublicMethodExists');
        $classname_method_exists1 = method_exists(TestClassExists::class, 'testPrivateMethodExists');
        $classname_method_exists2 = method_exists(TestClassExists::class, 'testProtectedMethodExists');
        $classname_method_exists3 = method_exists(TestClassExists::class, 'testPublicMethodExists');
        
        // Assert
        $this->assertTrue($object_method_exists1);
        $this->assertTrue($object_method_exists2);
        $this->assertTrue($object_method_exists3);
        $this->assertTrue($classname_method_exists1);
        $this->assertTrue($classname_method_exists2);
        $this->assertTrue($classname_method_exists3);
    }
    
    public function testPropertyExists()
    {
        // Arrange
        $test_class_exists = new TestClassExists();
        
        // Actual
        $private_property1   = property_exists($test_class_exists, 'testPrivatePropertyExists');
        $private_property2   = property_exists(TestClassExists::class, 'testPrivatePropertyExists');
        $protected_property1 = property_exists($test_class_exists, 'testProtectedPropertyExists');
        $protected_property2 = property_exists(TestClassExists::class, 'testProtectedPropertyExists');
        $public_property1    = property_exists($test_class_exists, 'testPublicPropertyExists');
        $public_property2    = property_exists(TestClassExists::class, 'testPublicPropertyExists');
        
        // Assert
        $this->assertTrue($private_property1);
        $this->assertTrue($private_property2);
        $this->assertTrue($protected_property1);
        $this->assertTrue($protected_property2);
        $this->assertTrue($public_property1);
        $this->assertTrue($public_property2);
    }
    
    public function testTraitExists()
    {
        // Arrange
        
        // Actual
        $test_trait_exists = trait_exists(TestTraitExists::class);
        
        // Assert
        $this->assertTrue($test_trait_exists);
    }
    
    public function testClassAlias()
    {
        // Arrange
        class_alias(TestClassExists::class, 'MyRightCapital\Container\Tests\AliasTestClassExists');
        $test_class_exists = new TestClassExists();
        
        // Actual
        $actual = new AliasTestClassExists();
        
        //Assert
        $this->assertInstanceOf(TestClassExists::class, $actual);
        $this->assertInstanceOf(AliasTestClassExists::class, $test_class_exists);
    }
    
    public function testGetClass()
    {
        // Arrange
        $test_class_exists = new TestClassExists();
        
        // Actual
        $class_name = get_class($test_class_exists);
        
        // Assert
        $this->assertSame(TestClassExists::class, $class_name);
    }
    
    public function testGetParentClass()
    {
        // Arrange
        $child_class = new ChildClass();
        
        // Actual
        $parent_class1 = get_parent_class($child_class);
        $parent_class2 = get_parent_class(ChildClass::class);
        
        // Assert
        $this->assertSame(ParentClass::class, $parent_class1);
        $this->assertSame(ParentClass::class, $parent_class2);
    }
    
    public function testGetCalledClass()
    {
        // Arrange
        $child_class  = new ChildClass();
        $parent_class = new ParentClass();
        
        // Actual
        $child_called_class  = $child_class->testGetCalledClass();
        $parent_called_class = $parent_class->testGetCalledClass();
        
        // Assert
        $this->assertSame(ChildClass::class, $child_called_class);
        $this->assertSame(ParentClass::class, $parent_called_class);
    }
    
    public function testInArray()
    {
        $this->assertTrue(in_array('a', ['a', 'b', 1], true));
    }
    
    public function testGetClassMethod()
    {
        // Arrange
        $get_class_methods1 = get_class_methods(ChildClass::class);
        $get_class_methods2 = get_class_methods(new ChildClass());
        
        // Actual
        
        // Assert
        $this->assertFalse(in_array('testPrivateGetClassMethod', $get_class_methods1, true));
        $this->assertFalse(in_array('testPrivateGetClassMethod', $get_class_methods2, true));
        $this->assertFalse(in_array('testProtectedGetClassMethod', $get_class_methods1, true));
        $this->assertFalse(in_array('testProtectedGetClassMethod', $get_class_methods2, true));
        $this->assertTrue(in_array('testPublicGetClassMethod', $get_class_methods1, true));
        $this->assertTrue(in_array('testPublicGetClassMethod', $get_class_methods2, true));
        $this->assertTrue(in_array('testGetCalledClass', $get_class_methods1, true));
        $this->assertTrue(in_array('testGetCalledClass', $get_class_methods2, true));
    }
    
    public function testGetClassVars()
    {
        // Arrange
        
        // Actual
        $class_vars = get_class_vars(ChildClass::class);
        
        // Assert
        $this->assertArrayNotHasKey('privateNoDefaultVar', $class_vars);
        $this->assertArrayNotHasKey('privateDefaultVar', $class_vars);
        $this->assertArrayNotHasKey('protectedNoDefaultVar', $class_vars);
        $this->assertArrayNotHasKey('protectedDefaultVar', $class_vars);
        $this->assertEmpty($class_vars['publicNoDefaultVar']);
        $this->assertEquals('public_laravel', $class_vars['publicDefaultVar']);
    }
    
    public function testGetObjectVars()
    {
        // Arrange
        $get_object_vars = new TestGetObjectVars(1, 2, 3);
        
        // Actual
        $object_vars = get_object_vars($get_object_vars);
        
        // Assert
        $this->assertArrayNotHasKey('x', $object_vars);
        $this->assertArrayNotHasKey('y', $object_vars);
        $this->assertEquals(3, $object_vars['z']);
        $this->assertArrayNotHasKey('dot1', $object_vars);
        $this->assertArrayNotHasKey('dot2', $object_vars);
        $this->assertArrayNotHasKey('circle1', $object_vars);
        $this->assertArrayNotHasKey('circle2', $object_vars);
        $this->assertEquals(10, $object_vars['line1']);
        $this->assertEmpty($object_vars['line2']);
    }
    
    public function testIsSubclassOf()
    {
        // Arrange
        $child_class = new ChildClass();
        
        // Actual
        $is_subclass = is_subclass_of($child_class, ParentClass::class);
        
        // Assert
        $this->assertTrue($is_subclass);
    }
    
    public function testIsA()
    {
        // Arrange
        $child_class = new ChildClass();
        
        // Actual
        $is_object = is_a($child_class, ChildClass::class);
        $is_subclass = is_a($child_class, ParentClass::class);
        
        // Assert
        $this->assertTrue($is_object);
        $this->assertTrue($is_subclass);
    }
}

class TestGetObjectVars
{
    private   $x;
    protected $y;
    public    $z;
    private   $dot1    = 10;
    private   $dot2;
    protected $circle1 = 20;
    protected $circle2;
    public    $line1   = 10;
    public    $line2;
    
    public function __construct($x, $y, $z)
    {
        
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }
}

class ChildClass extends ParentClass
{
    private   $privateNoDefaultVar;
    private   $privateDefaultVar   = 'private_laravel';
    protected $protectedNoDefaultVar;
    protected $protectedDefaultVar = 'protected_laravel';
    public    $publicNoDefaultVar;
    public    $publicDefaultVar    = 'public_laravel';
    
    private function testPrivateGetClassMethod()
    {
    }
    
    protected function testProtectedGetClassMethod()
    {
    }
    
    public function testPublicGetClassMethod()
    {
    }
}

class ParentClass
{
    public function testGetCalledClass()
    {
        return get_called_class();
    }
}

class TestClassExists
{
    private   $testPrivatePropertyExists;
    protected $testProtectedPropertyExists;
    public    $testPublicPropertyExists;
    
    private function testPrivateMethodExists()
    {
    }
    
    protected function testProtectedMethodExists()
    {
    }
    
    public function testPublicMethodExists()
    {
    }
}

interface TestInterfaceExists
{
    
}

trait TestTraitExists
{
    
}