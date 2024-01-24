<?php

use App\Http\Controllers\Github\WebhookController;
use App\Http\Controllers\RepositoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('v1')->group(function () {
    Route::post('/webhooks/set', [WebhookController::class, 'create'])->name('git.hook.set');
    Route::get('/webhooks/get', [WebhookController::class, 'show'])->name('git.repos.get');
    Route::delete('webhooks/delete', [WebhookController::class, 'delete'])->name('git.hook.delete');
    Route::post('/repositories/add', [RepositoryController::class, 'create'])->name('git.repos.add');
});

