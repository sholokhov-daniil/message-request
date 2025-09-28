<?php

namespace App\Service\Messengers\Telegram\Route;

use App\Service\Messengers\Telegram\Facade\TelegramMessenger;
use Telegram\Bot\Laravel\Facades\Telegram;

class CallbackRoute
{
    public function commandsHandler(): void
    {
        $update = Telegram::getWebhookUpdate();
        TelegramMessenger::getLogger()->debug($update->callbackQuery->data);

        list($action, $data) = explode('|', $update->callbackQuery->data, 2);

        $data = explode('|', $data);
        $handler = $this->getHandler($action);

        $handler($update->callbackQuery, $data);

        Telegram::answerCallbackQuery([
            'callback_query_id' => $update->callbackQuery->id,
        ]);
    }

    private function getHandler(string $action): ?callable
    {
        $handler = config(sprintf('%s.callback.%s', TelegramMessenger::getId(), $action));

        if (!$handler) {
            return null;
        }

        return new $handler;
    }
}
