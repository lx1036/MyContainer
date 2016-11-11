<?php

namespace MyRightCapital\Development\DecoratorPattern;

class Kernel implements IKernel
{
    public function handle()
    {
        echo 'Kernel handle the request, and send the response.' . PHP_EOL;
    }
}