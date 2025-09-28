<?php

namespace App\Service\Messengers\Telegram\CallbackQuery\Calendar\Request;

use App\Service\Messengers\Telegram\DTO\Callback;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\CallbackQuery;

class SelectedTimeCallback
{
    public function __invoke(CallbackQuery $query, array $data = []): void
    {
        $time = current($data);

        $keyboard = Keyboard::make()->inline();
        $keyboard->row([
            Keyboard::inlineButton([
                'text' => ' ✅ Записаться',
                'callback_data' => (string)(new Callback('calendar_request_accepted', $data)),
            ]),

            Keyboard::inlineButton([
                'text' => '❌ Назад',
                'callback_data' => (string)(new Callback('calendar_request_accepted_return', $data)),
            ]),
        ]);

        Telegram::editMessageText([
            'chat_id' => $query->message->chat->id,
            'message_id' => $query->message->messageId,
            'text' => 'Подтвердите запись: ' . $time,
            'reply_markup' => $keyboard->toJson()
        ]);
    }
}
