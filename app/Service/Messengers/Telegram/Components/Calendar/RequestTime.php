<?php

namespace App\Service\Messengers\Telegram\Components\Calendar;

use App\Components\ComponentInterface;
use App\Service\Messengers\Telegram\DTO\Callback;
use Telegram\Bot\Keyboard\Keyboard;

class RequestTime implements ComponentInterface
{
    /**
     * @param array $data
     * @return array|array[]
     */
    public function build(mixed $data): array
    {
        $keyboard = Keyboard::make()->inline();
        $this->createBody($keyboard, $data);
        $this->createFooter($keyboard, $data);

        return $keyboard->toArray();
    }


    private function createBody(Keyboard $keyboard, array $data): void
    {
        $row = [];
        $times = (array)($data['items'] ?? []);
        $action = (string)($data['action'] ?? []);

        foreach ($times as $value) {
            $row[] = $keyboard::inlineButton([
                'text' => $value,
                'callback_data' => $action . '|' . $value,
            ]);

            if (count($row) === 3) {
                $keyboard->row($row);
                $row = [];
            }
        }

    }

    private function createFooter(Keyboard $keyboard, array $data): void
    {
        $keyboard->row([
            $keyboard::inlineButton([
                'text' => 'Назад',
                'callback_data' => Callback::create('calendar_request', $data['date'])
            ])
        ]);
    }
}
