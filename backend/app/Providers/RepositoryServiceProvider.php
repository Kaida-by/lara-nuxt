<?php

namespace App\Providers;

use App\Http\Interfaces\ArticleRepositoryInterface;
use App\Http\Interfaces\PosterRepositoryInterface;
use App\Http\Repositories\ArticleRepository;
use App\Http\Repositories\PosterRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(PosterRepositoryInterface::class, PosterRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
