<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:project {name : The name of the project}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and initialize the project with database migrations and seeders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $this->info("Installing project: {$name}");

        $this->info('Running migrations and seeders...');
        $this->call('migrate:fresh', ['--seed' => true]);

        $this->info('Project installed successfully!');
    }
}
