<?php

include __DIR__ . '/../vendor/autoload.php';

use MyRightCapital\Container\Container;

$container = new Container();

// @feature 1. bind

// @feature 2. auto concrete resolution 
$container->bind(ContainerBinder::class);
$object = $container->make(ContainerBinder::class);
echo $object instanceof ContainerBinder === true;

// @feature 3. singleton bind



class ContainerBinder
{
    
}