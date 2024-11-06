<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDTO extends Command
{
    protected $signature = 'make:dto {name} {--folderName=} {--type=}';
    protected $description = 'Create a new DTO class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $folderName = $this->option('folderName');
        $type = $this->option('type');

        $this->createDto($name, $folderName, $type);
    }

    protected function createDirectory($path)
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    protected function createDto($name, $folderName = null, $type = null)
    {
        $dtoName = $name . ($type ? $type : '') . 'Dto';
        $default = $type == "Request" ? "Request" : "Response";
        $dtoPath = $folderName ?
            app_path("Domain/DTOs/{$default}/{$folderName}/{$dtoName}.php") :
            app_path("Domain/DTOs/{$default}/{$dtoName}.php");

        $this->createDirectory(dirname($dtoPath));

        $dtoContent = $this->generateDtoContent($dtoName, $default, $folderName);

        File::put($dtoPath, $dtoContent);

        if ($folderName) {
            $this->info("DTO created successfully in folder: {$folderName}.");
        } else {
            $this->info("DTO created successfully.");
        }
    }

    protected function generateDtoContent($dtoName,$default, $folderName = null)
    {

        $nameSpace = $folderName ? "App\Domain\DTOs\\{$default}\\{$folderName};" : "App\Domain\DTOs\\{$default};";
        return <<<EOT
<?php

 namespace {$nameSpace}

class {$dtoName}
{
    public function __construct(){}
    // Define your DTO properties and methods here
}
EOT;
    }
}
