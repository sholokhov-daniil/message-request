<?php

namespace App\Containers\Bot\Telegram\Providers;

use App\Containers\Bot\Telegram\Action\Api\Command\BookRequestAction;
use App\Containers\Bot\Telegram\Action\Api\Command\HelpAction;
use App\Containers\Bot\Telegram\Action\Api\Command\StartAction;
use App\Containers\Bot\Telegram\Registry\BotRegistry;
use App\Containers\Bot\Telegram\Messenger;
use App\Containers\Bot\Telegram\UI\API\Components\ComponentManager;
use App\Containers\Bot\Telegram\UI\API\Route\CallbackRoute;
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
            HelpAction::class,
            BookRequestAction::class,
            StartAction::class
        ]);

        $this->app->singleton(
            'MessengerTelegram',
            fn() => new Messenger
        );

        $this->app->singleton(
            'MessengerTelegramComponent',
            fn() => new ComponentManager
        );

        $this->app->singleton(
            'MessengerTelegramCallbackRoute',
            fn() => new CallbackRoute
        );

        $this->app->singleton(
            'TelegramBotRegistry',
            fn() => new BotRegistry()
        );
    }
}
