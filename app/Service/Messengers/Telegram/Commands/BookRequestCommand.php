<?php

namespace App\Service\Messengers\Telegram\Commands;

use App\Service\Messengers\Telegram\Facade\TelegramMessenger;
use Telegram\Bot\Commands\Command;

class BookRequestCommand extends Command
{
    protected string $name = 'book_request';
    protected string $description = 'Записаться';

    public function handle(): void
    {
        $data = ['now' => now()->locale('ru')];
        $component = TelegramMessenger::components()->buildCalendar($data);

        $this->replyWithMessage([
            'text' => 'Выберите дату для записи на услугу:',
            'reply_markup' => json_encode($component)
        ]);
    }
}
