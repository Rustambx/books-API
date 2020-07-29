<?php

namespace App\Modules\Taxonomy\Facades;

use Illuminate\Support\Facades\Facade;

class TaxonomyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'taxonomy';
    }
}