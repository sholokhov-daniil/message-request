<?php

namespace App\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

class StartCommand extends Command
{
    protected string $name = "start";
    protected string $description = "Начать работу с ботом";

    public function handle()
    {
        $this->replyWithMessage([
            'text' => 'Привет! Выберите дату записи:',
            'reply_markup' => $this->generateCalendar()
        ]);
    }

    private function generateCalendar(): array
    {
        $today = now();
        $keyboard = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $today->copy()->addDays($i);
            $keyboard[] = [Keyboard::inlineButton([
                'text' => $day->format('d.m'),
                'callback_data' => 'date_' . $day->format('Y-m-d'),
            ])];
        }

        return Keyboard::make([
            'inline_keyboard' => $keyboard,
        ])->toArray();
    }
}

