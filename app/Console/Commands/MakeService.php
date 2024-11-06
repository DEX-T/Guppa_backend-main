<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name} {--folderName=}';
    protected $description = 'Create a new interface and its implementation';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $folderName = $this->option('folderName');

        $this->createService($name, $folderName);
    }

    protected function createDirectory($path)
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    protected function createService($name, $folderName = null)
    {
        $interfaceName = "I".$name . 'Service';
        $serviceName = $name . 'Service';

        $interfacePath = $folderName ?
            app_path("Domain/Interfaces/{$folderName}/{$interfaceName}.php") :
            app_path("Domain/Interfaces/{$interfaceName}.php");

        $servicePath = $folderName ?
            app_path("Services/{$folderName}/{$serviceName}.php") :
            app_path("Services/{$serviceName}.php");

        $this->createDirectory(dirname($interfacePath));
        $this->createDirectory(dirname($servicePath));

        $interfaceContent = $this->generateInterfaceContent($interfaceName);
        $serviceContent = $this->generateServiceContent($serviceName, $folderName = null, $interfaceName);

        File::put($interfacePath, $interfaceContent);
        File::put($servicePath, $serviceContent);

        if ($folderName) {
            $this->info("Service interface and implementation created successfully in folder: {$folderName}.");
        } else {
            $this->info("Service interface and implementation created successfully.");
        }
    }

    protected function generateInterfaceContent($interfaceName,  $folderName = null)
    {
        $use = $folderName != null ?  "App\Domain\Interfaces\\{$folderName}\\{$interfaceName};"
        :  "App\Domain\Interfaces\\{$interfaceName};";


        return <<<EOT
<?php

namespace {$use};

interface {$interfaceName}
{
    // Define your service interface methods here
}
EOT;
    }

    protected function generateServiceContent($folderName,$serviceName,  $interfaceName)
    {
        $use = $folderName != null ?  "App\Domain\Interfaces\\{$folderName}\\{$interfaceName};"
        :  "App\Domain\Interfaces\\{$interfaceName};";

        $nameSpace = $folderName != null ? "App\Services\\{$folderName};" :
            "App\Services;";
        return <<<EOT
<?php

namespace {$nameSpace}

use {$use}

class {$serviceName} implements {$interfaceName}
{
    // Implement your service methods here
}
EOT;
    }
}

