<?php

declare(strict_types=1);

namespace App\Services\Github;

use Illuminate\Support\Facades\Http;

/**
 * Class for working with GitHub API
 */
class GithubService
{
    protected Http $http;
    private string $token;
    private string $version = '2022-11-28';
    protected string $redirectUrl;
    protected const API_URL = 'https://api.github.com';

    public function __construct(Http $http)
    {
        $this->http = $http;
        $this->token = env('GITHUB_TOKEN');
        $this->redirectUrl = env('GITHUB_REDIRECT_URL');
    }

    /**
     * Sets the headers for sending an HTTP request to GitHu
     */
    protected function setHeaders(): array
    {
        return [
            'Accept' => 'application/vnd.github+json',
            'Authorization' => "Bearer $this->token",
            'X-GitHub-Api-Version' => $this->version,
        ];
    }

    protected function getUser(): array
    {
        return $this->http::withHeaders($this->setHeaders())->get(self::API_URL . '/user')->json();
    }

}
