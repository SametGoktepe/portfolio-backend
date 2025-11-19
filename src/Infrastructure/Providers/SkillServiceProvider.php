<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\Skill\Repositories\SkillRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Repositories\EloquentSkillRepository;

class SkillServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            SkillRepositoryInterface::class,
            EloquentSkillRepository::class
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

