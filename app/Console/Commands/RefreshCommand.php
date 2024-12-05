<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class RefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears config, cache, and then optimize';

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
     * @return int
     */
    public function handle()
    {
        // $this->info('Clearing configuration cache...');
        $this->call('config:clear');

        // $this->info('Clearing application cache...');
        $this->call('cache:clear');

        // $this->info('Optimizing the application...');
        $this->call('optimize:clear');
        $this->call('queue:restart');
        Cache::flush();

        $this->info('Done! Configuration and cache cleared, and optimization completed.');
        return 0;
    }
}
