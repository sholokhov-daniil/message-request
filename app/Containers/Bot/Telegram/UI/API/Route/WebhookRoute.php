<?php

namespace App\Containers\Bot\Telegram\UI\API\Route;

use App\Containers\Bot\Telegram\Facade\TelegramMessenger;
use App\Core\Response\HttpResponse;
use Telegram\Bot\Api;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Objects\Update;
use App\Containers\Bot\Telegram\Facade\CallbackRoute;

class WebhookRoute
{
    public function __invoke(Api $bot): mixed
    {
        TelegramMessenger::getLogger()->debug(print_r($bot, true));
        $update = $bot->getWebhookUpdate();

        if ($this->isMessage($update)) {
            $response = $bot->commandsHandler(true);
        } elseif ($this->isCallback($update)) {
            $response = CallbackRoute::commandsHandler();
        } else {
            $response = new HttpResponse([])->addError('Действие не определено');
        }

        return $response;
    }

    /**
     * Проверка принадлежности запроса к сообщению
     *
     * @param Update $update
     * @return bool
     */
    private function isMessage(Update $update): bool
    {
        return $update->objectType() === 'message';
    }

    /**
     * Проверка принадлежности запроса к обратной связи
     *
     * @param Update $update
     * @return bool
     */
    private function isCallback(Update $update): bool
    {
        return $update->objectType() === 'callback_query';
    }
}
