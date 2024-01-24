<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use PhpParser\Node\Expr\Array_;
use Psy\Util\Json;
use Symfony\Component\HttpFoundation\Response;

class GithubService
{
    private Http $http;
    private string $token;
    private string $version = '2022-11-28';
    private string $redirectUrl;
    private const API_URL = 'https://api.github.com';

    public function __construct(Http $http, string $token, string $redirectUrl)
    {
        $this->http = $http;
        $this->token = $token;
        $this->redirectUrl = $redirectUrl;
    }

    private function setHeaders(): array
    {
        return [
            'Accept' => 'application/vnd.github+json',
            'Authorization' => "Bearer $this->token",
            'X-GitHub-Api-Version' => $this->version,
        ];
    }

    private function getUser(): array
    {
        return $this->http::withHeaders($this->setHeaders())->get(self::API_URL . '/user')->json();
    }

    public function getRepository(string $repository): array
    {
        $user = $this->getUser();
        return $this->http::withHeaders($this->setHeaders())
            ->get(self::API_URL . '/repos/' . $user['login'] . '/' . $repository . '/hooks')
            ->json();
    }

    public function setWebhook(string $repository, array $hooks)
    {
        $user = $this->getUser();

        $response = Http::withHeaders($this->setHeaders())
            ->post((self::API_URL . '/repos/' . $user['login'] . '/' . $repository . '/hooks'), [
                'name' => 'web',
                'active' => true,
                'events' => $hooks,
                'config' => [
                    'url' => $this->redirectUrl,
                    'content_type' => 'json',
                    'insecure_ssl' => '0',
                ],
            ]);

        if ($response->status() === Response::HTTP_NOT_FOUND) {
            return [
                'message' => 'Github repository is not found',
                'code' => Response::HTTP_NOT_FOUND
            ];
        }

        if ($response->status() === Response::HTTP_UNPROCESSABLE_ENTITY) {
            return [
                'message' => 'Webhook is registered already',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY
            ];
        }

        if ($response->status() === Response::HTTP_FORBIDDEN) {
            return [
                'message' => 'Forbidden',
                'code' => Response::HTTP_FORBIDDEN
            ];
        }

        return [
            'message' => 'Webhook successfully registered',
            'repository' => $repository,
            'hooks' => $hooks,
            'code' => Response::HTTP_CREATED
        ];
    }

    public function getRepositoryHooks(string $repository)
    {
        $user = $this->getUser();
        $response = $this->http::withHeaders($this->setHeaders())
            ->get(self::API_URL . '/repos/' . $user['login'] . '/' . $repository . '/hooks');

        if ($response->status() === 404) {
            return [
                'message' => 'Github repository is not found',
                'code' => Response::HTTP_NOT_FOUND
            ];
        }

        if (empty($response->json())) {
            return [
                'message' => 'Hooks not found',
                'code' => Response::HTTP_OK
            ];
        }

        return [
            'repository' => $repository,
            'hooks' => $response[0]['events'],
            'code' => Response::HTTP_OK
        ];
    }
}
