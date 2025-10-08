<?php

namespace App\Containers\Bot\Telegram\Action\Api\Command;

use App\Core\Parent\Actions\Action;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

/**
 * Обработка команды боту
 *
 * @final
 */
final class CommandAction extends Action
{
    public function __invoke(): Update|array
    {
        return $this->run();
    }

    public function run(): Update|array
    {
        return Telegram::commandsHandler(true);
    }
}
