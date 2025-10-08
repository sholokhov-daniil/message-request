<?php

namespace App\Containers\Bot\Telegram\UI\API\Components;

use App\Containers\Bot\Telegram\Facade\TelegramMessenger;
use App\Core\UI\Components\ComponentInterface;
use App\Facade\ComponentFacade;
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

    public function buildRequestTime(array $data): array
    {
        return $this->build('request_time', $data);
    }

    public function buildCalendarMonth(array $data): array
    {
        return $this->build('calendar_month_select', $data);
    }

    public function buildCalendarYear(array $data): array
    {
        return $this->build('calendar_year_select', $data);
    }

    public function buildMenu(): array
    {
        return $this->build('menu');
    }
}
