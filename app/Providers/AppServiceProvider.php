<?php

namespace App\Providers;

use App\Services\GithubService;
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
            return new GithubService(new Http(), env('GITHUB_TOKEN'), env('GITHUB_REDIRECT_URL'));
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
