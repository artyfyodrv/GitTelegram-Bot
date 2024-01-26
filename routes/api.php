<?php

use App\Http\Controllers\Github\WebhookController;
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
    Route::get('/webhooks/get/', [WebhookController::class, 'show'])->name('git.hook.get');
    Route::delete('/webhooks/delete/{id}', [WebhookController::class, 'delete'])->name('git.hook.delete');
});

