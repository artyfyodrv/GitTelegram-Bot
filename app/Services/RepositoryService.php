<?php

namespace App\Services;

use App\Models\Repository;
use Symfony\Component\HttpFoundation\Response;

class RepositoryService extends GithubService
{
    public function create(string $repository)
    {
        $isExists = $this->get($repository);

        if (!($isExists['code'] === 200)) {
            return $isExists;
        }

        Repository::query()->create([
            'name' => $repository,
            'owner' => $this->getUser()['login'],
        ]);

        return [
            'message' => 'Repository success added',
            'repository' => $repository,
            'repository_id' => $isExists['data']['id'],
            'code' => Response::HTTP_CREATED
        ];
    }

    public function get(string $repository)
    {
        $user = $this->getUser();
        $response = $this->http::withHeaders($this->setHeaders())
            ->get(self::API_URL . '/repos/' . $user['login'] . '/' . $repository);

        if ($response->status() === 404) {
            return [
                'message' => 'Github repository is not found',
                'code' => Response::HTTP_NOT_FOUND
            ];
        }

        if ($response->status() === 301) {
            return [
                'message' => 'Moved permanently',
                'code' => Response::HTTP_MOVED_PERMANENTLY
            ];
        }

        if ($response->status() === 403) {
            return [
                'message' => 'Forbidden',
                'code' => Response::HTTP_FORBIDDEN
            ];
        }

        return [
            'data' => $response->json(),
            'code' => Response::HTTP_OK
        ];
    }
}
