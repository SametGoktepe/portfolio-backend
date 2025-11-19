<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\Education\Repositories\EducationRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Repositories\EloquentEducationRepository;

class EducationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            EducationRepositoryInterface::class,
            EloquentEducationRepository::class
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

