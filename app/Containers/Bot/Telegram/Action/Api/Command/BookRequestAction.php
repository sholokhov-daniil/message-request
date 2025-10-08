<?php

namespace App\Containers\Bot\Telegram\Action\Api\Command;

use App\Containers\Bot\Telegram\Facade\TelegramMessenger;
use Telegram\Bot\Commands\Command;

class BookRequestAction extends Command
{
    protected string $name = 'book_request';
    protected string $description = 'Записаться';

    public function handle(): void
    {
        $data = ['now' => now()->locale('ru')];
        $markup = TelegramMessenger::components()->buildCalendar($data);

        $this->replyWithMessage([
            'text' => '📅 Выберите дату для записи на услугу:',
            'reply_markup' => json_encode($markup)
        ]);
    }
}
