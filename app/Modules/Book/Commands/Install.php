<?php


namespace App\Modules\Book\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:install';

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

    public function handle()
    {
        return true;
    }
}
