<?php

namespace App\Service\Messengers\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\BotCommand;

class HelpCommand extends Command
{
    protected string $name = 'help';
    protected string $description = 'Справка по командам';

    public function handle(): void
    {
        $message = array_map(
            fn(BotCommand $command) => sprintf('/%s - %s', $command->command, $command->description),
            Telegram::getMyCommands()
        );

        $this->replyWithMessage(['text' => implode(PHP_EOL, $message)]);
    }
}
