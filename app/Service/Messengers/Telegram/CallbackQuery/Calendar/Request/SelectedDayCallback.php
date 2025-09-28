<?php

namespace App\Service\Messengers\Telegram\CallbackQuery\Calendar\Request;

use App\Service\Messengers\Telegram\Facade\TelegramMessenger;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\CallbackQuery;

class SelectedDayCallback
{
    public function __invoke(CallbackQuery $query, array $data = []): void
    {
        // TODO: Добавить логику поиска свободного время
        $componentData = [
            'action' => 'calendar_request_time',
            'items' => [
                "13:00",
                "14:00",
                "15:00",
                "18:00",
                "22:00",
                "3:00",
            ]
        ];


        $component = TelegramMessenger::components()->buildRequestTime($componentData);

        Telegram::editMessageText([
            'chat_id' => $query->message->chat->id,
            'message_id' => $query->message->messageId,
            'text' => 'Выберите время приема',
            'reply_markup' => json_encode($component),
        ]);
    }
}
