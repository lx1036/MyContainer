<?php

namespace MyRightCapital\Development\DecoratorPattern;

class Request implements IRequest
{
    /**
     * @var \MyRightCapital\Development\DecoratorPattern\IKernel
     */
    private $kernel;

    public function __construct(IKernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function handle()
    {
        echo 'This request has been filtering by the before action in the middlewares, and go into the kernel.' . PHP_EOL;
        $this->kernel->handle();
        echo 'The request has been handled by the kernel, and will be send to the after action in the middlewares' . PHP_EOL;
    }

    public function getRequest()
    {
        return $this;
    }
}