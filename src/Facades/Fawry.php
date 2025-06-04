<?php

namespace AymanElshehawy\LaravelFawry\Facades;

use Illuminate\Support\Facades\Facade;

class Fawry extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'fawry';
    }
} 