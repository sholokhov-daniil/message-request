<?php

namespace App\Service\Messengers\Telegram\Components\Calendar;

use App\Components\ComponentInterface;
use App\Service\Messengers\Telegram\DTO\Callback;
use Carbon\Carbon;
use Telegram\Bot\Keyboard\Keyboard;

class MonthSelectComponent implements ComponentInterface
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
        $action = (string)($data['action'] ?? 'calendar_request');
        $lineSize = intval($data['size'] ?? 2);

        /** @var Carbon $item */
        foreach ($times as $item) {
            $row[] = $keyboard::inlineButton([
                'text' => $item->isoFormat('MMMM'),
                'callback_data' => Callback::create(
                    $action,
                    [
                        $data['date'][0],
                        $item->month
                    ]
                ),
            ]);

            if (count($row) === $lineSize) {
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
