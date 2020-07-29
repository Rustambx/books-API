<?php

namespace App\Modules\Condition;

use App\Modules\Condition\Command\Install;
use App\Modules\Condition\Services\ConditionService;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');

        $this->loadViewsFrom(__DIR__.'/Views', 'condition');
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ConditionService::class, function () {
            return new ConditionService;
        });

        $this->app->alias(ConditionService::class, 'condition');
    }
}
