<?php

namespace App\Containers\Bot\Telegram\Action\Api\Command;

use App\Containers\Bot\Telegram\Facade\TelegramMessenger;
use Telegram\Bot\Commands\Command;

class StartAction extends Command
{
    protected string $name = 'start';
    protected string $description = 'Начать работу с ботом';

    public function handle(): void
    {
        // TODO: Записать пользователя в базу
        $text = "Добро пожаловать в систему записи! 👋" . PHP_EOL;
        $text .= "Здесь вы можете:" . PHP_EOL;

        $this->replyWithMessage([
            'text' => $text,
            'reply_markup' => json_encode(TelegramMessenger::components()->buildMenu()),
        ]);
    }
}
