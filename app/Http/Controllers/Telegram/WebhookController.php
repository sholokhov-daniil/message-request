<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;

use App\Service\Messengers\Telegram\Facade\TelegramMessenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;
use Throwable;

class WebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            return Telegram::commandsHandler(true);
        } catch (Throwable $throwable) {
            TelegramMessenger::getLogger()->alert($throwable);
            $this->sendErrorMessage();

            return response()->json(['ok' => false], 200);
        }
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
