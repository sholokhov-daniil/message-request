<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/telegram-reg', function() {
    Telegram::setWebhook([
        'url' => env('TELEGRAM_WEBHOOK_URL')
    ]);

    $response = Telegram::getWebhookInfo();
    dd($response);
});

Route::get('/telegram-commands', function() {
    Telegram::deleteMyCommands();

    $res = Telegram::setMyCommands([
        'commands' => [
            ['command' => 'start', 'description' => 'Начало работы с ботом'],
            ['command' => 'book_request', 'description' => 'Записаться']
        ],
    ]);

    return response()->json(['success' => $res]);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__ . '/integration.php';
