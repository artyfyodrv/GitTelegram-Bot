<?php

namespace App\Providers;

use App\Services\GithubService;
use App\Services\RepositoryService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GithubService::class, function ($app) {
            return new GithubService(new Http());
        });
        $this->app->bind(RepositoryService::class, function ($app) {
            return new RepositoryService(new Http());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
