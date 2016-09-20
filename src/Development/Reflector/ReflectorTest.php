<?php

class ConstructorParameter
{

}

class ReflectorTest
{
    private $refletorProperty1;

    protected $refletorProperty2;

    public $refletorProperty3;

    /**
     * @var int
     */
    private $request;

    public function __construct(int $request = 10, string $response, ConstructorParameter $constructorParameter, Closure $closure)
    {

        $this->request = $request;
    }

    private function reflectorMethod1()
    {
    }

    protected function reflectorMethod2()
    {
    }

    public function reflectorMethod3()
    {
    }
}

$reflector_class        = new ReflectionClass(ReflectorTest::class);
$methods                = $reflector_class->getMethods();
$properties             = $reflector_class->getProperties();
$constructor            = $reflector_class->getConstructor();
$constructor_parameters = $constructor->getParameters();

foreach ($constructor_parameters as $constructor_parameter) {
    $dependency = $constructor_parameter->getClass();
    var_dump($dependency);

    if ($constructor_parameter->isDefaultValueAvailable()) {
        var_dump($constructor_parameter->getDefaultValue());
    }
}

var_dump($methods);
var_dump($properties);
var_dump($constructor);
var_dump($constructor_parameters);
