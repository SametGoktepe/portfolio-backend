<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\Hobby\Repositories\HobbyRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Repositories\EloquentHobbyRepository;

class HobbyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            HobbyRepositoryInterface::class,
            EloquentHobbyRepository::class
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

