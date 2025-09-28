<?php

namespace App\Providers;

use App\Service\Messengers\Telegram\Messenger;
use App\Service\Messengers\Telegram\Commands\HelpCommand;
use App\Service\Messengers\Telegram\Commands\StartCommand;
use App\Service\Messengers\Telegram\Commands\BookRequestCommand;
use App\Service\Messengers\Telegram\Components\ComponentManager;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Telegram::addCommands([
            HelpCommand::class,
            BookRequestCommand::class,
            StartCommand::class
        ]);

        $this->app->singleton(
            'MessengerTelegram',
            fn() => new Messenger
        );

        $this->app->singleton(
            'MessengerTelegramComponent',
            fn() => new ComponentManager
        );
    }
}
