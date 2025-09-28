<?php

namespace App\Service\Messengers\Telegram\Registry;

use App\Components\ComponentInterface;
use App\Service\Messengers\Telegram\Facade\TelegramMessenger;

class ComponentRegistry
{
    public function getCalendar(): ?ComponentInterface
    {
        return TelegramMessenger::getComponent('calendar');
    }
}
