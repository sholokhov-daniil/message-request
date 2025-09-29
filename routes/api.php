<?php

use App\Service\Messengers\Telegram\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/telegram/webhook', WebhookController::class)
//    ->middleware('telegram')
//    ->middleware('integration')
    ->name('telegram.webhook');
