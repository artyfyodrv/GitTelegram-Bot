<?php

declare(strict_types=1);

namespace App\Http\Controllers\Github;

use App\Http\Controllers\Controller;
use App\Services\Github\WebhooksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function create(Request $request, WebhooksService $webhooksService): JsonResponse
    {
        $webhooksService = $webhooksService->set($request->get('repository'), $request->get('hooks', []));

        return response()->json($webhooksService, $webhooksService['code']);
    }

    public function show(Request $request, WebhooksService $webhooksService): JsonResponse
    {
        $webhooksService = $webhooksService->get($request->get('repository'));

        return response()->json($webhooksService, $webhooksService['code']);
    }

    public function delete(int $id, Request $request, WebhooksService $webhooksService): JsonResponse
    {
        $webhooksService = $webhooksService->delete($id, $request->get('repository'));

        return response()->json($webhooksService, $webhooksService['code']);
    }
}
