<?php

namespace App\Service\Messengers\Telegram\CallbackQuery\Calendar\Request;

use App\Service\Messengers\Telegram\Facade\TelegramMessenger;
use Carbon\Carbon;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\CallbackQuery;

class CalendarRequestCallback
{
    public function __invoke(CallbackQuery $query, array $data = []): void
    {
        $componentData = [
            'now' => Carbon::createFromDate($data[0], $data[1])->locale('ru'),
        ];

        $component = TelegramMessenger::components()->buildCalendar($componentData);

        Telegram::editMessageText([
            'chat_id' => $query->message->chat->id,
            'message_id' => $query->message->messageId,
            'text' => 'Выберите дату для записи на услугу',
            'reply_markup' => json_encode($component),
        ]);
    }
}
