<?php

namespace App\Modules\Taxonomy;

use App\Modules\Taxonomy\Services\TaxonomyManager;
use Illuminate\Support\ServiceProvider as BaseProvider;
use Illuminate\Support\Facades\View;
use Route;

class ServiceProvider extends BaseProvider
{
    protected $commands = [
        \App\Modules\Taxonomy\Commands\Install::class,
    ];

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');

        $this->loadViewsFrom(__DIR__.'/Views', 'taxonomy');

        // Explicit model binding
        Route::bind('vid', function ($id) {
            return \App\Modules\Taxonomy\Models\Vocabulary::findOrFail($id);
        });

        Route::bind('tid', function ($id) {
            return \App\Modules\Taxonomy\Models\Term::findOrFail($id);
        });

        // Cpanel menu items
        View::composer('cpanel::navbar-top', function ($view) {
            $viewData = $view->getData();

            if (isset($viewData['menu'])) {
                $menu = $viewData['menu']->toArray();

                if (isset($menu['structure'])) {
                    $menu['structure']['children'][] = [
                        'label' => 'Taxonomy',
                        'link' => route('taxonomy')
                    ];
                }
            }

            $view->with(compact('menu'));
        });
    }

    public function register()
    {
        $this->app->singleton(TaxonomyManager::class, function () {
            return new TaxonomyManager;
        });

        $this->app->alias(TaxonomyManager::class, 'taxonomy');

        // Register commands
        $this->commands($this->commands);
    }
}