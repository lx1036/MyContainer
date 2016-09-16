<?php

namespace MyRightCapital\Development\DecoratorPattern;

interface IRequest extends IMiddleware
{
    public function getRequest();
}