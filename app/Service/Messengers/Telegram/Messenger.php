<?php

namespace App\Service\Messengers\Telegram;

use App\Components\ComponentInterface;
use App\Service\Messengers\MessengerInterface;

use App\Service\Messengers\Telegram\Components\ComponentManager;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Message;

class Messenger implements MessengerInterface
{
    public function getId(): string
    {
        return 'telegram';
    }

    /**
     * Формирует и отправляет сообщение с ошибкой
     *
     * @param int|null $chatId
     * @return void
     */
    public function sendErrorMessage(int $chatId = null): void
    {
        $chatId ??= self::getChatId();

        if (!$chatId) {
            return;
        }

        self::sendMessage([
            'chat_id' => $chatId,
            'text' => config('telegram.error_message')
        ]);
    }

    /**
     * Сформировать сообщение, для Telegram
     *
     * @param array $params
     * @return Message
     */
    public function sendMessage(array $params): Message
    {
        return Telegram::sendMessage($params);
    }

    /**
     * Производит проверку принадлежности запроса к callback
     *
     * @return bool
     */
    public function isCallbackQuery(): bool
    {
        $update = Telegram::getWebhookUpdate();
        return $update->objectType() === 'callback_query';
    }

    /**
     * Производит проверку, что текущий запрос принадлежит к webhook
     *
     * @return bool
     */
    public function isWebhook(): bool
    {
        return !empty(Telegram::getWebhookInfo()->get('url'));
    }

    /**
     * @return int|null
     */
    public function getChatId(): ?int
    {
        $update = Telegram::getWebhookUpdate();

        if ($update?->getMessage()) {
            return $update->message->chat->id;
        }

        if ($update?->callbackQuery) {
            return $update->callbackQuery->message->chat->id;
        }

        return null;
    }

    public function getComponent(string $name): ?ComponentInterface
    {
        return $this->components()->build($name);
    }

    public function components(): ComponentManager
    {
        return app('MessengerTelegramComponent');
    }

    public function getLogger(): LoggerInterface
    {
        return Log::channel($this->getId());
    }
}
