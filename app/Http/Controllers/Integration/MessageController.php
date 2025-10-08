<?php

namespace App\Http\Controllers\Integration;

use App\Core\Models\Bot;
use App\Core\Models\User;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function __invoke(User $user)
    {
        $integration = Bot::query()->where('user_id', $user->id)->first();

        dd($integration);
//        $integration = new Integration();
//        $integration->service_id = Service::query()->first()->id;
//        $integration->user_id = 1;
//        $integration->token = '8373741066:AAFZoNfjFEFadGZ3_cQXrelmzQHkXtEq_Qg';
//        $integration->verified = true;
//
//        $integration->save();

        dd($integration);
    }
}
