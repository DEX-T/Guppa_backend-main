<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}  {--folderName=}';
    protected $description = 'Create a new repository interface and implementation';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $interfaceName = "I".$name;
        $folderName = $this->option('folderName');
      

        $interfacePath = $folderName ? app_path("Repositories/Contracts/{$folderName}/{$interfaceName}Repository.php") 
        :  app_path("Repositories/Contracts/{$interfaceName}Repository.php");

        $repositoryPath =  $folderName ? app_path("Repositories/{$folderName}/{$name}Repository.php")
        : app_path("Repositories/{$name}Repository.php");

        $this->createDirectory(dirname($interfacePath));
        $this->createDirectory(dirname($repositoryPath));

        $interfaceContent = $this->generateInterfaceContent($interfaceName);
        $repositoryContent = $this->generateRepositoryContent($folderName = null, $name, $interfaceName);

        File::put($interfacePath, $interfaceContent);
        File::put($repositoryPath, $repositoryContent);

        $this->info("Repository interface and implementation created successfully.");
    }

    protected function createDirectory($path)
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    protected function generateInterfaceContent($name)
    {
        return <<<EOT
<?php

namespace App\Repositories\Contracts;

interface {$name}Repository
{
    // Define your repository methods here
}
EOT;
    }

    protected function generateRepositoryContent($folderName = null, $name, $interfaceName)
    {
        $use = $folderName ?  "App\Repositories\Contracts\\{$folderName}\\{$interfaceName}Repository;"
        :  "App\Repositories\Contracts\\{$interfaceName}Repository;";

        return <<<EOT
<?php

namespace App\Repositories;

use {$use}
class {$name}Repository implements {$interfaceName}Repository
{
    
    // Implement your repository methods here
}
EOT;
    }
}
