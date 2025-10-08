<?php

namespace App\Containers\Bot\Telegram\Action\Api\Callback\Calendar\Request;

use App\Containers\Bot\Telegram\Facade\TelegramMessenger;
use Carbon\Carbon;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\CallbackQuery;

class YearSelectCallback
{
    public function __invoke(CallbackQuery $query, array $data = []): void
    {
        // TODO: Добавить логику поиска свободного время
        $free = [
            Carbon::create(2025)->locale('ru'),
            Carbon::create(2024)->locale('ru'),
        ];

        $componentData = [
            'date' => $data,
            'items' => $free
        ];

        $component = TelegramMessenger::components()->buildCalendarYear($componentData);

        Telegram::editMessageText([
            'chat_id' => $query->message->chat->id,
            'message_id' => $query->message->messageId,
            'text' => '📅 Выберите желаемый год',
            'reply_markup' => json_encode($component),
        ]);
    }
}
