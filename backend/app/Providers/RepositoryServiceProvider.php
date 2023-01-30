<?php

namespace App\Providers;

use App\Http\Interfaces\ArticleRepositoryInterface;
use App\Http\Repositories\ArticleRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
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
