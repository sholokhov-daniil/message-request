<?php

namespace App\Containers\Bot\Telegram\Registry;

use App\Core\Models\Bot;
use App\Containers\Bot\Telegram\Builder\BotBuilder;

use Illuminate\Support\Collection;

use Telegram\Bot\Api;

class BotRegistry
{
    private Collection $bots;

    public function __construct()
    {
        $this->bots = collect();
    }

    /**
     * Проверка наличия зарегистрированного telegram бота
     *
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return $this->bots->has($id);
    }

    /**
     * Получение бота по id
     *
     * @param string $id
     * @return Api|null
     */
    public function get(string $id): ?Api
    {
        return $this->bots->get($id);
    }

    /**
     * Регистрирует бота в системе
     *
     * @param Bot $bot
     * @return Api
     */
    public function registration(Bot $bot): Api
    {
        $telegram = BotBuilder::create($bot->token);
        $this->bots->put($bot->id, $telegram);

        return $telegram;
    }
}
