<?php

namespace App\Service\Messengers\Telegram\CallbackQuery\Calendar\Request;

use App\Service\Messengers\Telegram\DTO\Callback;
use App\Service\Messengers\Telegram\Facade\TelegramMessenger;
use Carbon\Carbon;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\CallbackQuery;

class AcceptRequestCallback
{
    public function __invoke(CallbackQuery $query, array $data = []): void
    {
        TelegramMessenger::getLogger()->debug($data);

        list($year, $month, $day, $time) = $data;
        list($hour, $minute) = explode(':', $time);

        $date = Carbon::createFromDate($year, $month, $day)->locale('ru');
        $date->setTime($hour, $minute);

        // TODO: Берем из БД
        $message = "Запись подтверждена!✅\n\nЖдем Вас по адресу\n📍 Алтайский край, г. Барнаул, д. 299\n\nДата записи\n {{date}}";

        $message = str_replace('{{date}}', $date->isoFormat('D MMMM YYYY H:mm'), $message);

        Telegram::editMessageText([
            'chat_id' => $query->message->chat->id,
            'message_id' => $query->message->messageId,
            'text' => $message
        ]);
    }
}
