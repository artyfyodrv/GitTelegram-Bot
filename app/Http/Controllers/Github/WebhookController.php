<?php

namespace App\Http\Controllers\Github;

use App\Http\Controllers\Controller;
use App\Services\GithubService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function setWebhook(Request $request,GithubService $githubService)
    {
        $repository = $request->get('repository');
        $hooks = $request->get('hooks', []);
        return $githubService->setWebhook($repository, $hooks);
    }
}
