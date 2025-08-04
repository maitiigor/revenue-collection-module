<?php

namespace Maitiigor\Payroll\Facades;

use Illuminate\Support\Facades\Facade;

class RevenueCollection extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'RevenueCollection';
    }
}
