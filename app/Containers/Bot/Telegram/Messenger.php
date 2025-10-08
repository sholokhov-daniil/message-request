<?php

namespace App\Containers\Bot\Telegram;

use App\Containers\Bot\Telegram\Registry\BotRegistry;
use App\Containers\Bot\Telegram\UI\API\Components\ComponentManager;
use App\Core\Models\Bot;
use App\Core\UI\Components\ComponentInterface;
use App\Service\Messengers\MessengerInterface;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Telegram\Bot\Api;
use Telegram\Bot\BotsManager;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Message;

class Messenger implements MessengerInterface
{
    /**
     * @var Bot[]
     */
    private array $managers = [];

    public function getId(): string
    {
        return 'telegram';
    }

    /**
     * Получение реестра ботов
     *
     * @return BotRegistry
     */
    public function getBotRegistry(): BotRegistry
    {
        return app('TelegramBotRegistry');
    }

    public function getBot(Bot $bot): Api
    {
        $registry = $this->getBotRegistry();

        if (!$registry->has($bot->id)){
            $registry->registration($bot);
        }

        return $registry->get($bot->id);
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
     * @param BotsManager $manager
     * @return int|null
     */
    public function getChatId(): ?int
    {
        $update = $manager->getWebhookUpdate();

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
        return $this->components()->get($name);
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
