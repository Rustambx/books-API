<?php

namespace App\Modules\User\Facades;

use Illuminate\Support\Facades\Facade;

class UserFacade extends Facade
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
        return 'user';
    }
}
