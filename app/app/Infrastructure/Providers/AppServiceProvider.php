<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Domain\Repositories\CategoryRepositoryInterface;
use App\Domain\Repositories\PostRepositoryInterface;
use App\Domain\Repositories\TagRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\CategoryRepository;
use App\Infrastructure\Repositories\Eloquent\PostRepository;
use App\Infrastructure\Repositories\Eloquent\TagRepository;
use App\Infrastructure\Services\RedisCacheService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->singleton(RedisCacheService::class, function ($app) {
            return new RedisCacheService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
