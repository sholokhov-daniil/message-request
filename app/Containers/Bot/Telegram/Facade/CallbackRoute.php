<?php

namespace App\Containers\Bot\Telegram\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array commandsHandler()
 */
class CallbackRoute extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'MessengerTelegramCallbackRoute';
    }
}
