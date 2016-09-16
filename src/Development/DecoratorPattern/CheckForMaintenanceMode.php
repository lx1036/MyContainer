<?php

namespace MyRightCapital\Development\DecoratorPattern;

class CheckForMaintenanceMode implements IMiddleware
{
    /**
     * @var \MyRightCapital\Development\DecoratorPattern\IMiddleware
     */
    private $middleware;

    /**
     * CheckForMaintenanceMode constructor.
     *
     * @param \MyRightCapital\Development\DecoratorPattern\IMiddleware $middleware
     */
    public function __construct(IMiddleware $middleware)
    {
        $this->middleware = $middleware;
    }
    
    public function handle()
    {
        echo 'Check if the application is in the maintenance status.' . PHP_EOL;
        $this->middleware->handle();
    }
}