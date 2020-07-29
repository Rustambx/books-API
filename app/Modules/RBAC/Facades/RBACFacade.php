<?php

namespace App\Modules\RBAC\Facades;

use Illuminate\Support\Facades\Facade;

class RBACFacade extends Facade
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
        return 'rbac';
    }    
}