<?php

namespace App\Modules\Comment\Facades;

use Illuminate\Support\Facades\Facade;

class CommentFacade extends Facade
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
        return 'comment';
    }
}
