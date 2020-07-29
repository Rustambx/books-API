<?php

namespace App\Modules\Comment;

use App\Modules\Comment\Services\CommentService;
use App\Modules\Comment\Commands\Install;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    protected $commands = [
        Install::class,
    ];

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');

        $this->loadViewsFrom(__DIR__.'/Views', 'comment');
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(CommentService::class, function () {
            return new CommentService;
        });

        $this->app->alias(CommentService::class, 'comment');

        $this->commands($this->commands);
    }
}
