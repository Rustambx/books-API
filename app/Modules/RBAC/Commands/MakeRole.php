<?php

namespace App\Modules\RBAC\Commands;

use Illuminate\Console\Command;
use RBAC;

class MakeRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:role {slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $slug = $this->arguments()['slug'];

        if (RBAC::findRole($slug)) {
            return false;
        }

        RBAC::makeRole($slug);

        return true;
    }
}