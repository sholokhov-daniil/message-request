<?php

namespace App\Service\Messengers\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Стартовое приветствие';

    public function handle(): void
    {
        $this->replyWithMessage(['text' => 'Добро пожаловать!']);
    }
}
