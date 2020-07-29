<?php

namespace App\Modules\Book\Facades;

use Illuminate\Support\Facades\Facade;

class BookFacade extends Facade
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
        return 'book';
    }
}
