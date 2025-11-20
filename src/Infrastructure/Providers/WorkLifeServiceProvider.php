<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\WorkLife\Repositories\WorkLifeRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Repositories\EloquentWorkLifeRepository;

class WorkLifeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            WorkLifeRepositoryInterface::class,
            EloquentWorkLifeRepository::class
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

