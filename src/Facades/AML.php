<?php

namespace Cloudspace\AML\Facades;

use Illuminate\Support\Facades\Facade;

class AML extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'aml';
    }
}
