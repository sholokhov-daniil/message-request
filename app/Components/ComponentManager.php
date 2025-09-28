<?php

namespace App\Components;

use Illuminate\Contracts\Container\BindingResolutionException;

class ComponentManager
{
    /**
     * Проверка наличия компонента
     *
     * @param string $messenger
     * @param string $name
     * @return bool
     */
    public static function has(string $messenger, string $name): bool
    {
        return !empty(static::search($messenger, $name));
    }

    /**
     * Производится инициализация компонента
     *
     * @param string $messenger
     * @param string $name
     * @return ComponentInterface|null
     * @throws BindingResolutionException
     */
    public static function getComponent(string $messenger, string $name): ?ComponentInterface
    {
        $component = static::search($messenger, $name);

        if (!$component || !is_subclass_of($component, ComponentInterface::class)) {
            return null;
        }

        return app()->make($component);
    }

    /**
     * Поиск компонента в конфигурациях
     *
     * @param string $messenger
     * @param string $name
     * @return string
     */
    private static function search(string $messenger, string $name): string
    {
        return config("$messenger.components.$name");
    }
}
