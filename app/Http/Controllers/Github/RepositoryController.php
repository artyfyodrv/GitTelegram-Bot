<?php

declare(strict_types=1);

namespace App\Http\Controllers\Github;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddRepositoryRequest;
use App\Services\Github\RepositoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RepositoryController extends Controller
{
    public function create(AddRepositoryRequest $request,RepositoryService $repositoryService): JsonResponse
    {
        return response()->json($repositoryService->add($request->get('repository')));
    }

    public function show(Request $request, RepositoryService $repositoryService): JsonResponse
    {
        return response()->json($repositoryService->getFromGit($request->get('repository')));
    }
}
