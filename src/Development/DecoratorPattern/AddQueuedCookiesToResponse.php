<?php

namespace MyRightCapital\Development\DecoratorPattern;

class AddQueuedCookiesToResponse implements IMiddleware
{
    /**
     * @var \MyRightCapital\Development\DecoratorPattern\IMiddleware
     */
    private $middleware;

    /**
     * AddQueuedCookiesToResponse constructor.
     *
     * @param \MyRightCapital\Development\DecoratorPattern\IMiddleware $middleware
     */
    public function __construct(IMiddleware $middleware)
    {
        $this->middleware = $middleware;
    }
    
    public function handle()
    {
        $this->middleware->handle();
        echo 'Add queued cookies to the response.' . PHP_EOL;
    }
}