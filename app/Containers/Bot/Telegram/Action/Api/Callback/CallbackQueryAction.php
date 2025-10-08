<?php

namespace App\Containers\Bot\Telegram\Action\Api\Callback;

use App\Containers\Bot\Telegram\Facade\CallbackRoute;
use App\Core\Parent\Actions\Action;

/**
 * Обработки действия пользователя
 *
 * @final
 */
final class CallbackQueryAction extends Action
{
    public function __invoke(): array
    {
        return $this->run();
    }

    public function run(): array
    {
        return CallbackRoute::commandsHandler() ?: [];
    }
}
