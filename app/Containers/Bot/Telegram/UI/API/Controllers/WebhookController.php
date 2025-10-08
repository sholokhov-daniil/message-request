<?php

namespace App\Containers\Bot\Telegram\UI\API\Controllers;

use App\Containers\Bot\Telegram\Registry\BotRegistry;
use App\Containers\Bot\Telegram\Facade\CallbackRoute;
use App\Containers\Bot\Telegram\Facade\TelegramMessenger;
use App\Containers\Bot\Telegram\UI\API\Route\WebhookRoute;
use App\Core\Models\Bot;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

class WebhookController extends Controller
{
    public function __invoke(Request $request, Bot $bot): mixed
    {
        try {
            $telegram = TelegramMessenger::getBot($bot);
//            $telegram->bot()->commandsHandler()

            $route = new WebhookRoute;

            $response = $route($telegram);

//            if ($telegram->isWebhook()) {
//                $response = $telegram->getManager()->commandsHandler(true);
//            } elseif ($telegram->isCallbackQuery()) {
//                $response = CallbackRoute::commandsHandler();
//            }

        } catch (Throwable $throwable) {
            TelegramMessenger::getLogger()->alert($throwable);
            $this->sendErrorMessage();
            return  response()->json(['ok' => false], 200);
        }


//        try {
//            if (TelegramMessenger::isWebhook()) {
//                $response =  Telegram::commandsHandler(true);
//            } elseif (TelegramMessenger::isCallbackQuery()) {
//                $response = CallbackRoute::commandsHandler() ?: [];
//            } else {
//                $response = response()->json(['status' => false, 'message' => 'Действие не определено']);
//            }
//        } catch (Throwable $throwable) {
//            TelegramMessenger::getLogger()->alert($throwable);
//            $this->sendErrorMessage();
//
//            $response = response()->json(['ok' => false], 200);
//        }
//
//        TelegramMessenger::getLogger()->debug($response);

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
