<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\Project\Repositories\ProjectRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Repositories\EloquentProjectRepository;

class ProjectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProjectRepositoryInterface::class,
            EloquentProjectRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

