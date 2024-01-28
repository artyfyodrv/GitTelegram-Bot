<?php

declare(strict_types=1);

namespace App\Services\Github;

use App\Models\Repository;
use Symfony\Component\HttpFoundation\Response;

class RepositoryService extends GithubService
{
    /**
     * Method for add repository to Database
     */
    public function add(string $repository): array
    {
        $repoGitExists = $this->getFromGit($repository);

        if ($repoGitExists['code'] !== Response::HTTP_OK) {
            return $repoGitExists;
        }

        $user = $this->getUser();
        $reposData = Repository::query()->create([
            'name' => $repository,
            'owner' => $user['login'],
        ]);

        return [
            'message' => 'Repository successfully added in system',
            'data' => $reposData
        ];
    }

    /**
     * Get data repository from GitHub
     *
     * @param string $repository - Name repository from GitHub
     * @return array - Data from repository GitHub
     */
    public function getFromGit(string $repository): array
    {
        $user = $this->getUser();
        $repository = $this->http::withHeaders($this->setHeaders())
            ->get(self::API_URL . '/repos/' . $user['login'] . '/' . $repository);

        if ($repository->status() === Response::HTTP_NOT_FOUND) {
            return [
                'message' => 'Repository is not found in GitHub',
                'code' => Response::HTTP_NOT_FOUND
            ];
        }

        if ($repository->status() === Response::HTTP_MOVED_PERMANENTLY) {
            return [
                'message' => 'Moved permanently',
                'code' => Response::HTTP_MOVED_PERMANENTLY
            ];
        }

        if ($repository->status() === Response::HTTP_FORBIDDEN) {
            return [
                'message' => 'Forbidden',
                'code' => Response::HTTP_FORBIDDEN
            ];
        }

        return [
            'data' => $repository->json(),
            'code' => Response::HTTP_OK
        ];
    }

    public function getFromDb(string $repository): array
    {
        $repository = Repository::query()->where('name', $repository)->first();

        if (!$repository) {
            return [
                'message' => 'Repository not found in system, please add first',
                'code' => Response::HTTP_NOT_FOUND
            ];
        }

        return [
            'data' => $repository,
            'code' => Response::HTTP_OK
        ];
    }
}
