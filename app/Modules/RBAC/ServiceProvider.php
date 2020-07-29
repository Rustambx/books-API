<?php

namespace App\Modules\RBAC;

use App\Modules\RBAC\Providers\RBACUserProvider;
use App\Modules\RBAC\Services\RBACService;
use Auth;
use Blade;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    protected $commands = [
        \App\Modules\RBAC\Commands\MakePermission::class,
        \App\Modules\RBAC\Commands\MakeRole::class,
        \App\Modules\RBAC\Commands\AssignPermissionToRole::class,
        \App\Modules\RBAC\Commands\AssignRoleToUser::class,
        \App\Modules\RBAC\Commands\UpdateRoleCache::class,
        \App\Modules\RBAC\Commands\Install::class,
    ];

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $router = $this->app['router'];
        $router->aliasMiddleware('ability', \App\Modules\RBAC\Middleware\Ability::class);
        $router->aliasMiddleware('permission', \App\Modules\RBAC\Middleware\Permission::class);
        $router->aliasMiddleware('role', \App\Modules\RBAC\Middleware\Role::class);

        Auth::provider('rbac', function ($app, array $config) {
            return new RBACUserProvider($app['hash'], $config['model']);
        });

        $this->registerBladeDirectives();
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(RBACService::class, function ($app) {
            return new RBACService($app);
        });

        $this->app->alias(RBACService::class, 'rbac');

        $this->commands($this->commands);
    }

    protected function registerBladeDirectives()
    {
        if (!class_exists('\Blade')){
            return;
        }

        Blade::directive('role', function($expression) {
            return "<?php if (\\RBAC::hasRole({$expression})) : ?>";
        });

        Blade::directive('endrole', function($expression) {
            return "<?php endif; // RBAC::hasRole ?>";
        });

        Blade::directive('permission', function($expression) {
            return "<?php if (\\RBAC::can({$expression})) : ?>";
        });

        Blade::directive('endpermission', function($expression) {
            return "<?php endif; // RBAC::can ?>";
        });

        Blade::directive('ability', function($expression) {
            return "<?php if (\\RBAC::ability({$expression})) : ?>";
        });

        Blade::directive('endability', function($expression) {
            return "<?php endif; // RBAC::ability ?>";
        });
    }
}