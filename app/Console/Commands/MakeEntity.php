<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEntity extends Command
{
    protected $signature = 'make:entity {name}';
    protected $description = 'Create a new entity';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $name = $name."Entity";
        $entityPath = app_path("Domain/Entities/{$name}.php");

        $this->createDirectory(dirname($entityPath));

        $entityContent = $this->generateEntityContent($name);

        File::put($entityPath, $entityContent);

        $this->info("Entity created successfully.");
    }

    protected function createDirectory($path)
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    protected function generateEntityContent($name)
    {
        return <<<EOT
<?php

namespace App\Domain\Entities;

class {$name}
{
    public function __construct(){}
    // Define your entity properties and methods here
}
EOT;
    }
}
