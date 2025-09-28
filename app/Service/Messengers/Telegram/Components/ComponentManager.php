<?php

namespace App\Service\Messengers\Telegram\Components;

use App\Facade\ComponentFacade;
use App\Components\ComponentInterface;
use App\Service\Messengers\Telegram\Facade\TelegramMessenger;

use Psr\Container\ContainerInterface;

readonly class ComponentManager implements ContainerInterface
{
    private string $messenger;

    public function __construct()
    {
        $this->messenger = TelegramMessenger::getId();
    }

    public function has(string $id): bool
    {
        return ComponentFacade::has($this->messenger, $id);
    }

    public function get(string $id): ?ComponentInterface
    {
        return ComponentFacade::getComponent($this->messenger, $id);
    }

    public function build(string $id, mixed $data = null): array
    {
        return $this->get($id)?->build($data) ?: [];
    }

    public function buildCalendar(array $data = []): array
    {
        return $this->build('calendar', $data);
    }

    public function buildMenu(): array
    {
        return $this->build('menu');
    }
}
