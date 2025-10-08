<?php

namespace App\Containers\Bot\Telegram\Action\Api\Callback\Calendar\Request;

use App\Containers\Bot\Telegram\Facade\TelegramMessenger;
use Carbon\Carbon;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\CallbackQuery;

class SelectTimeCallback
{
    public function __invoke(CallbackQuery $query, array $data = []): void
    {
        // TODO: Добавить логику поиска свободного время
        $componentData = [
            'date' => $data,
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

        list($year, $month, $day) = $data;
        $date = Carbon::createFromDate($year, $month, $day)->locale('ru');

        Telegram::editMessageText([
            'chat_id' => $query->message->chat->id,
            'message_id' => $query->message->messageId,
            'text' => '🕝 Выберите время приема на ' . $date->isoFormat('D MMMM YYYY'),
            'reply_markup' => json_encode($component),
        ]);
    }
}
