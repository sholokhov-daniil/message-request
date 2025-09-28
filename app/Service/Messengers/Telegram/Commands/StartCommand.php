<?php

namespace App\Service\Messengers\Telegram\Commands;

use App\Service\Messengers\Telegram\Facade\TelegramMessenger;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Начать работу с ботом';

    public function handle(): void
    {
        // TODO: Записать пользователя в базу
        $text = "Добро пожаловать в систему записи! 👋" . PHP_EOL;
        $text .= "Здесь вы можете:\n" . PHP_EOL;
        $text .= "• 📅 Записаться на услугу" . PHP_EOL;
        $text .= "• 📋 Просмотреть свои записи" . PHP_EOL;
        $text .= "Выберите нужное действие:";

        $this->replyWithMessage([
            'text' => $text,
            'reply_markup' => json_encode(TelegramMessenger::components()->buildMenu()),
        ]);
    }
}
