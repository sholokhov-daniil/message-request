<?php

namespace App\Service\Messengers\Telegram\CallbackQuery\Calendar\Request;

use App\Service\Messengers\Telegram\DTO\Callback;
use App\Service\Messengers\Telegram\Facade\TelegramMessenger;
use Carbon\Carbon;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\CallbackQuery;

class MonthSelectCallback
{
    public function __invoke(CallbackQuery $query, array $data = []): void
    {
        // TODO: Добавить логику поиска свободного время
        $freeMonths = [
            Carbon::create(2025, 1)->locale('ru'),
            Carbon::create(2025, 4)->locale('ru'),
            Carbon::create(2025, 5)->locale('ru'),
            Carbon::create(2025, 9)->locale('ru'),
            Carbon::create(2025, 11)->locale('ru'),
        ];

        $componentData = [
            'date' => $data,
            'items' => $freeMonths
        ];

        $component = TelegramMessenger::components()->buildCalendarMonth($componentData);

        Telegram::editMessageText([
            'chat_id' => $query->message->chat->id,
            'message_id' => $query->message->messageId,
            'text' => '📅 Выберите желаемый месяц',
            'reply_markup' => json_encode($component),
        ]);
    }
}
