<?php

namespace App\Containers\Bot\Telegram\Builder;

use App\Containers\Bot\Telegram\Facade\TelegramMessenger;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotBuilder
{
    public static function create(string $token): Api
    {
        $config = config('telegram');

        $telegram = new Api(
            $token,
            $config['async_requests'] ?? false,
            $config['http_client_handler'] ?? null,
            $config['base_bot_url'] ?? null,
        );

        $commands = data_get($config, 'commands', []);

        TelegramMessenger::getLogger()->debug($commands);

        $commands = Telegram::parseBotCommands($commands);

        $telegram->addCommands($commands);

        return $telegram;
    }
}
