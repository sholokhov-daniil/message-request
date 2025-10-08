<?php

use App\Containers\Bot\Telegram\UI\API\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;


Route::prefix('telegram')
    ->middleware(['integration'])
    ->group(function () {
        Route::post('/webhook/{bot}', WebhookController::class)
            ->name('telegram.webhook');
    });
