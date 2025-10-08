<?php

namespace App\Containers\Bot\Telegram\UI\API\Components;

use App\Core\UI\Components\ComponentInterface;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\BotCommand;

class MenuComponent implements ComponentInterface
{
    public function build(mixed $data): array
    {
        $keyboard = Keyboard::make()->inline();

        $keyboard->row([
            $keyboard::inlineButton([
                'text' => "Сделать запись",
                'web_app' => ['url' => "https://u190407.test-handyhost.ru/telegram/web"]
            ])
        ]);

        return $keyboard->toArray();
    }
}
