<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRepositoryRequest;
use App\Services\RepositoryService;
use Illuminate\Http\Request;

class RepositoryController extends Controller
{
    public function create(AddRepositoryRequest $request, RepositoryService $repositoryService)
    {
        $repositoryService = $repositoryService->create($request->get('repository'));
        return response()->json($repositoryService, $repositoryService['code']);
    }
}
