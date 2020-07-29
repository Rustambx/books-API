<?php

namespace App\Modules\Taxonomy\Commands;

use Illuminate\Console\Command;
use RBAC;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taxonomy:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a list of permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        $permissions = [
            'manage-taxonomy'
        ];

        $role = RBAC::getAdminRole();

        foreach ($permissions as $perm) {
            $permission = RBAC::permission();
            $permission->name = $perm;
            $permission->save();

            $role->attachPermission($permission);

            $role->updatePermissionsCache();
        }

        $this->info('Taxonomy installed.');

        return true;
    }
}
