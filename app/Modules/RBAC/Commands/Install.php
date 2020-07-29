<?php

namespace App\Modules\RBAC\Commands;

use Artisan;
use Illuminate\Console\Command;
use RBAC;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rbac:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the RBAC module';

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
     * @return mixed
     */
    public function handle()
    {
        if (RBAC::roles()->isNotEmpty() || RBAC::permissions()->isNotEmpty()) {
            $this->info('RBAC module is installed already.');

            return false;
        }

        // Create the core roles.
        Artisan::call('make:role', [
            'slug' => 'superadmin'
        ]);
        Artisan::call('make:role', [
            'slug' => 'admin'
        ]);
        Artisan::call('make:role', [
            'slug' => 'employee'
        ]);
        Artisan::call('make:role', [
            'slug' => 'client'
        ]);

        $this->info('RBAC module installed.');
    }
}