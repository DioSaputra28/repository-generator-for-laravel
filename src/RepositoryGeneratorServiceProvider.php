<?php

namespace Diosaputra\RepositoryGenerator;

use Diosaputra\RepositoryGenerator\Commands\MakeRepositoryCommand;
use Illuminate\Support\ServiceProvider;

class RepositoryGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeRepositoryCommand::class,
            ]);
        }
    }
}