<?php

namespace App\Modules\Condition\Facades;

use Illuminate\Support\Facades\Facade;

class ConditionFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'condition';
    }
}
