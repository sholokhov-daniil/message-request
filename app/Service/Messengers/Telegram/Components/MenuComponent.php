<?php

namespace App\Service\Messengers\Telegram\Components;

use App\Components\ComponentInterface;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\BotCommand;

class MenuComponent implements ComponentInterface
{
    public function build(mixed $data): array
    {
        $keyboard = Keyboard::make()->inline();
        $iterator = Telegram::getMyCommands();

        /** @var BotCommand $command */
        foreach ($iterator as $command) {
            if ($command->command === 'start') {
                continue;
            }

            $keyboard->row([
                $keyboard::inlineButton([
                    'text' => $command->description,
                    'callback_data' => "CMD|{$command->command}"
                ])
            ]);
        }

        return $keyboard->toArray();
    }
}
