<?php

namespace App\Service\Messengers\Telegram\CallbackQuery\Calendar\Request;

use App\Service\Messengers\Telegram\DTO\Callback;
use App\Service\Messengers\Telegram\Facade\TelegramMessenger;
use Carbon\Carbon;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\CallbackQuery;

class SelectedTimeCallback
{
    public function __invoke(CallbackQuery $query, array $data = []): void
    {
        TelegramMessenger::getLogger()->debug($data);

        list($year, $month, $day, $time) = $data;

        $keyboard = Keyboard::make()->inline();
        $keyboard->row([
            Keyboard::inlineButton([
                'text' => ' ✅ Записаться',
                'callback_data' => Callback::create('calendar_request_accepted', $data),
            ]),

            Keyboard::inlineButton([
                'text' => '❌ Назад',
                'callback_data' => Callback::create('calendar_request_day', $data)
            ]),
        ]);

        $date = Carbon::createFromDate($year, $month, $day)->locale('ru');

        Telegram::editMessageText([
            'chat_id' => $query->message->chat->id,
            'message_id' => $query->message->messageId,
            'text' => sprintf('Подтвердите запись: %s %s', $date->isoFormat('D MMMM YYYY'), $time),
            'reply_markup' => $keyboard->toJson()
        ]);
    }
}
