<?php

declare(strict_types=1);

namespace App\Http\Controllers\Github;

use App\Http\Controllers\Controller;
use App\Services\GithubService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function create(Request $request, GithubService $githubService): JsonResponse
    {
        $githubService = $githubService->setWebhook($request->get('repository'), $request->get('hooks', []));

        return response()->json($githubService);
    }

    public function show(Request $request, GithubService $githubService): JsonResponse
    {
        $githubService = $githubService->getRepositoryHooks($request->get('repository'));

        return response()->json($githubService);
    }
}
