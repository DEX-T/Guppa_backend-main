<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateSingle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:single {migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a single migration class';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $migration = $this->argument('migration');

        if (class_exists($migration)) {
            $this->info("Running migration: $migration");
            Artisan::call('migrate', [
                '--pretend' => true, // Optional: show what would be done
            ]);
            (new $migration)->up();
            $this->info("Migration $migration executed successfully.");
        } else {
            $this->error("Migration class $migration does not exist.");
        }
    }
}
