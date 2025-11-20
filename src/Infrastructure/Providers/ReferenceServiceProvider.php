<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\Reference\Repositories\ReferenceRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Repositories\EloquentReferenceRepository;

class ReferenceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ReferenceRepositoryInterface::class,
            EloquentReferenceRepository::class
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

