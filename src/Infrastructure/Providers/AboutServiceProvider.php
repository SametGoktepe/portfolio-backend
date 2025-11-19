<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\About\Repositories\AboutRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Repositories\EloquentAboutRepository;
use Src\Domain\About\Services\AboutService;
use Src\Application\About\Commands\{
    CreateAboutCommand,
    UpdateAboutCommand,
    DeleteAboutCommand
};
use Src\Application\About\Queries\GetAboutQuery;
use Src\Infrastructure\Services\ImageUploadService;

class AboutServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Repository Binding
        $this->app->bind(
            AboutRepositoryInterface::class,
            EloquentAboutRepository::class
        );

        // Service Binding
        $this->app->bind(AboutService::class, function ($app) {
            return new AboutService(
                $app->make(AboutRepositoryInterface::class)
            );
        });

        // Image Service Binding
        //$this->app->singleton(ImageUploadService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}