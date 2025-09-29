<?php

namespace App\Service\Messengers\Telegram\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Service\Messengers\Telegram\Facade\CallbackRoute;
use App\Service\Messengers\Telegram\Facade\TelegramMessenger;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

class WebhookController extends Controller
{
    public function __invoke(Request $request): mixed
    {
        try {
            if (TelegramMessenger::isWebhook()) {
                $response =  Telegram::commandsHandler(true);
            } elseif (TelegramMessenger::isCallbackQuery()) {
                $response = CallbackRoute::commandsHandler() ?: [];
            } else {
                $response = response()->json(['status' => false, 'message' => 'Действие не определено']);
            }
        } catch (Throwable $throwable) {
            TelegramMessenger::getLogger()->alert($throwable);
            $this->sendErrorMessage();

            $response = response()->json(['ok' => false], 200);
        }

        return $response;
    }

    private function sendErrorMessage(): void
    {
        try {
            TelegramMessenger::sendErrorMessage();
        } catch (Throwable $e) {
            TelegramMessenger::getLogger()->alert($e);
        }
    }
}
