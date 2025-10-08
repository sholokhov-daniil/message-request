<?php

namespace App\Containers\Bot\Telegram\UI\WEB\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class WebAppController
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Telegram/Web');
    }
}
