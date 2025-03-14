<?php

namespace Diosaputra\RepositoryGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'make:repository {name} {--type=} {--force}';
    
    protected $description = 'Generate repository with Eloquent, Query Builder, or API';

    public function handle()
    {
        $name = $this->argument('name');
        $type = strtolower($this->option('type') ?? '');
        $force = $this->option('force');

        if (!$type) {
            $repoPath = app_path("Repositories/{$name}Repository.php");
            
            if (File::exists($repoPath) && !$force) {
                $this->error("Repository already exists: {$repoPath}");
                $this->info("Use --force to overwrite existing repository.");
                return;
            }
            
            File::ensureDirectoryExists(app_path('Repositories'));
            File::put($repoPath, $this->repositoryTemplate($name));
            $this->info("Repository created: {$repoPath}");
            return;
        }

        $validTypes = ['eloquent', 'query', 'api'];
        $types = explode(',', $type);
        $createdTypes = [];
        
        foreach ($types as $singleType) {
            $normalizedType = strtolower(trim($singleType));
            
            if (!in_array($normalizedType, $validTypes)) {
                $this->error("Invalid type '{$normalizedType}': choose eloquent, query, or api.");
                continue;
            }
            
            $interfacePath = app_path("Repositories/Interface/{$name}RepositoryInterface.php");
            $interfaceExists = File::exists($interfacePath);
            
            if (!$interfaceExists || $force) {
                File::ensureDirectoryExists(app_path('Repositories/Interface'));
                File::put($interfacePath, $this->interfaceTemplate($name));
                $this->info("Interface created: {$interfacePath}");
            } else {
                $this->line("Interface already exists: {$interfacePath}");
            }
            
            $repoTypePath = app_path("Repositories/" . ucfirst($normalizedType) . "/{$name}Repository" . ucfirst($normalizedType) . ".php");
            
            if (File::exists($repoTypePath) && !$force) {
                $this->warn("Repository already exists: {$repoTypePath}");
                $this->line("Use --force to overwrite existing repository.");
                continue;
            }
            
            File::ensureDirectoryExists(app_path("Repositories/" . ucfirst($normalizedType)));
            File::put($repoTypePath, $this->repositoryTemplateWithType($name, ucfirst($normalizedType)));
            $this->info("Repository created: {$repoTypePath}");
            $createdTypes[] = $normalizedType;
        }
        
        if (empty($createdTypes)) {
            $this->line("No new repositories were created.");
        } else {
            $this->info("Successfully created repositories of type: " . implode(', ', $createdTypes));
        }
    }

    protected function repositoryTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Repositories;

class {$name}Repository
{
    public function getAll()
    {
        // Implement getAll()
    }

    public function findById(\$id)
    {
        // Implement findById()
    }
}
PHP;
    }

    protected function repositoryTemplateWithType($name, $type)
    {
        return <<<PHP
<?php

namespace App\Repositories\\{$type};

use App\Repositories\Interface\\{$name}RepositoryInterface;

class {$name}Repository{$type} implements {$name}RepositoryInterface
{
    public function getAll()
    {
        // Implement getAll()
    }

    public function findById(\$id)
    {
        // Implement findById()
    }
}
PHP;
    }

    protected function interfaceTemplate($name)
    {
        return <<<PHP
<?php

namespace App\Repositories\Interface;

interface {$name}RepositoryInterface
{
    public function getAll();
    public function findById(\$id);
}
PHP;
    }
}

