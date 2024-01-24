<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GithubService
{
    private Http $http;
    private string $token;

    private string $redirectUrl;
    private const API_URL = 'https://api.github.com';
    public function __construct(Http $http)
    {
        $this->http = $http;
        $this->token = env('GITHUB_TOKEN');
        $this->redirectUrl = env('GITHUB_REDIRECT_URL');
    }
}
