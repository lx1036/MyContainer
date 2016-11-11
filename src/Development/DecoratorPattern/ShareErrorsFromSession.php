<?php

namespace MyRightCapital\Development\DecoratorPattern;

class ShareErrorsFromSession implements IMiddleware
{
    /**
     * @var \MyRightCapital\Development\DecoratorPattern\IMiddleware
     */
    private $middleware;

    /**
     * ShareErrorsFromSession constructor.
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
        echo 'Share the errors variable from request to the views.' . PHP_EOL;
    }
}