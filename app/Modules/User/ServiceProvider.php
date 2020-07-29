<?php

namespace App\Modules\User;

use App\Modules\User\Commands\Install;
use App\Modules\User\Services\UserService;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    protected $commands = [
        Install::class
    ];

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');

        $this->loadViewsFrom(__DIR__.'/Views', 'user');
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(UserService::class, function () {
            return new UserService();
        });

        $this->app->alias(UserService::class, 'user');

        $this->commands($this->commands);
    }
}
