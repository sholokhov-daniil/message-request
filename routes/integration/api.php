<?php

use App\Http\Controllers\Integration\RegistrationIntegrationController;
use Illuminate\Support\Facades\Route;

@include_once 'telegram.php';

Route::prefix('integration')
    ->group(function() {
        Route::get('/registration/{user}', RegistrationIntegrationController::class)
            ->name('integration.registration');

    });
