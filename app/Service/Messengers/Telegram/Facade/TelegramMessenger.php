<?php

namespace App\Service\Messengers\Telegram\Facade;

use App\Components\ComponentInterface;
use App\Service\Messengers\Telegram\Registry\ComponentRegistry;

use Illuminate\Support\Facades\Facade;
use Psr\Log\LoggerInterface;
use Telegram\Bot\Objects\Message;

/**
 * @method static string getId() Идентификатор месседжера
 * @method static ComponentInterface|null getComponent(string $name) Получение компонента мессенджера
 * @method static LoggerInterface getLogger() Механизм журналирования месседжера
 * @method static ComponentRegistry components() Реест доступных компонентов
 * @method static int|null getChatId() Предоставляет текущий id чата
 * @method static bool isWebhook() Проверяет принадлежность к webhook запросу
 * @method static bool isCallbackQuery() Проверяет принадлежность к CallbackQuery запросу
 * @method static Message sendMessage() Формирует сообщение для telegram
 * @method static void sendErrorMessage() Отправляет сообщение с ошибкой
 */
class TelegramMessenger extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'MessengerTelegram';
    }
}
