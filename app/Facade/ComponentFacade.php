<?php

namespace App\Facade;

use App\Components\ComponentInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool has(string $messenger, string $name) Проверяет наличие компонента
 * @method static ComponentInterface|null getComponent(string $messenger, string $name) Получение компонента мессенджера
 */
class ComponentFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'MessengerComponent';
    }
}
