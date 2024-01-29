<?php

declare(strict_types=1);

namespace App\Services\Github;

use App\Models\Webhook;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class for working with webhooks in GitHub repositories
 */
class WebhooksService extends GithubService
{

    public function set(string $repository, array $hooks): array
    {
        $repositoryService = new RepositoryService($this->http);
        $repoDb = $repositoryService->getFromDb($repository);


        if ($repoDb['code'] !== Response::HTTP_OK) {
            return $repoDb;
        }

        if ($repositoryService->getFromGit($repository)['code'] !== Response::HTTP_OK) {
            return $repositoryService->getFromGit($repository);
        }

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

        $hookId = $response->json()['id'];

        foreach ($hooks as $hook) {
            Webhook::query()->create([
                'name' => $hook,
                'repository_id' => $repoDb['data']['id'],
                'hook_git_id' => $hookId,
            ]);
        }

        return [
            'message' => 'Webhook successfully registered',
            'repository' => $repository,
            'hooks' => $hooks,
            'code' => Response::HTTP_CREATED
        ];
    }

    public function get(string $repository): array
    {
        $user = $this->getUser();
        $response = $this->http::withHeaders($this->setHeaders())
            ->get(self::API_URL . '/repos/' . $user['login'] . '/' . $repository . '/hooks');

        if ($response->status() === Response::HTTP_NOT_FOUND) {
            return [
                'message' => 'Github repository is not found',
                'code' => Response::HTTP_NOT_FOUND
            ];
        }

        if (empty($response->json())) {
            return [
                'message' => 'Registered hooks on repository not found',
                'code' => Response::HTTP_NOT_FOUND
            ];
        }

        return [
            'repository' => $repository,
            'hooks' => $result ?? $response[0]['events'],
            'code' => Response::HTTP_OK
        ];
    }

    public function delete(int $hookId, string $repository): array
    {
        $user = $this->getUser();
        $response = $this->http::withHeaders($this->setHeaders())
            ->delete(self::API_URL . '/repos/' . $user['login'] . '/' . $repository . '/hooks/' . $hookId);

        if ($response->status() === Response::HTTP_NOT_FOUND) {
            return [
                'message' => 'Hook in repository not found',
                'repository' => $repository,
                'hookId' => $hookId,
                'code' => Response::HTTP_NOT_FOUND
            ];
        }

        return [
            'message' => 'Hook successfully deleted',
            'repository' => $repository,
            'hookId' => $hookId,
            'code' => Response::HTTP_OK
        ];
    }

}
