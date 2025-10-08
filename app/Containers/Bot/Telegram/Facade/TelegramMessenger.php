<?php

namespace App\Containers\Bot\Telegram\Facade;

use App\Containers\Bot\Telegram\UI\API\Components\ComponentManager;
use App\Core\Models\Bot;
use App\Core\UI\Components\ComponentInterface;
use Illuminate\Support\Facades\Facade;
use Psr\Log\LoggerInterface;
use Telegram\Bot\Api;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Objects\Message;

/**
 * @method static string getId() Идентификатор месседжера
 * @method static ComponentInterface|null getComponent(string $name) Получение компонента мессенджера
 * @method static LoggerInterface getLogger() Механизм журналирования месседжера
 * @method static ComponentManager components() Реест доступных компонентов
 * @method static int|null getChatId() Предоставляет текущий id чата
 * @method static bool isWebhook() Проверяет принадлежность к webhook запросу
 * @method static bool isCallbackQuery() Проверяет принадлежность к CallbackQuery запросу
 * @method static Message sendMessage() Формирует сообщение для telegram
 * @method static void sendErrorMessage() Отправляет сообщение с ошибкой
 * @method static Api getBot(Bot $bot) Создание менеджера бота
 */
class TelegramMessenger extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'MessengerTelegram';
    }
}
