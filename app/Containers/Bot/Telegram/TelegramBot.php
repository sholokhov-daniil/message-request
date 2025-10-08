<?php

namespace App\Containers\Bot\Telegram;

use Telegram\Bot\BotsManager;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Message;

class TelegramBot
{
    private readonly BotsManager $manager;

    public function __construct(BotsManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Формирует и отправляет сообщение с ошибкой
     *
     * @param int|null $chatId
     * @return void
     * @throws TelegramSDKException
     */
    public function sendErrorMessage(?int $chatId = null): void
    {
        $chatId ??= $this->getChatId();

        if (!$chatId) {
            return;
        }

        $this->sendMessage([
            'chat_id' => $chatId,
            'text' => config('telegram.error_message')
        ]);
    }

    /**
     * Сформировать сообщение, для Telegram
     *
     * @param array $params
     * @return Message
     * @throws TelegramSDKException
     */
    public function sendMessage(array $params): Message
    {
        return $this->manager->sendMessage($params);
    }

    /**
     * Производит проверку принадлежности запроса к callback
     *
     * @return bool
     */
    public function isCallbackQuery(): bool
    {
        return $this->manager->getWebhookUpdate()->objectType() === 'callback_query';
    }

    /**
     * Производит проверку, что текущий запрос принадлежит к webhook
     *
     * @return bool
     */
    public function isWebhook(): bool
    {
        return $this->manager->getWebhookUpdate()->objectType() === 'message';
    }

    /**
     * Получение ID текущего чата
     *
     * @return int|null
     */
    public function getChatId(): ?int
    {
        $update = $this->manager->getWebhookUpdate();

        if ($update?->getMessage()) {
            return $update->message->chat->id;
        }

        if ($update?->callbackQuery) {
            return $update->callbackQuery->message->chat->id;
        }

        return null;
    }

    public function getManager(): BotsManager
    {
        return $this->manager;
    }
}
