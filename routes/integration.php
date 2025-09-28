<?php

use App\Http\Controllers\Telegram\WebhookController;
use Illuminate\Support\Facades\Route;

Route::any('/telegram/webhook', WebhookController::class)
    ->middleware('telegram')
    ->middleware('integration')
    ->name('telegram.webhook');


