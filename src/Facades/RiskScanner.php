<?php

namespace Cloudspace\AML\Facades;

use Illuminate\Support\Facades\Facade;

class RiskScanner extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'RiskScanner';
    }
}
