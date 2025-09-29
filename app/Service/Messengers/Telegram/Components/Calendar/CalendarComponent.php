<?php

namespace App\Service\Messengers\Telegram\Components\Calendar;

use App\Components\ComponentInterface;
use App\Service\Messengers\Telegram\DTO\Callback;
use App\Service\Messengers\Telegram\Facade\TelegramMessenger;
use Carbon\Carbon;
use Telegram\Bot\Keyboard\Keyboard;

class CalendarComponent implements ComponentInterface
{
    /**
     * @param array $data
     * @return array|array[]
     */
    public function build(mixed $data): array
    {
        $date = $data['now'];
        $selected = $data['selected'] ?? 0;

        if (!($date instanceof Carbon)) {
            return [];
        }

        $keyboard = Keyboard::make()->inline();
        $this->createHeader($keyboard, $date);
        $this->createBody($keyboard, $date);

        return $keyboard->toArray();
    }

    /**
     * Генерация шапки календаря
     *
     * @param Keyboard $keyboard
     * @param Carbon $date
     * @return void
     */
    private function createHeader(Keyboard $keyboard, Carbon $date): void
    {
        $prevDate = $date->copy();
        TelegramMessenger::getLogger()->debug([
            'PREV' => $prevDate->toString(),
            'NOW' => now()->toString()
        ]);

        $prevDate->subMonth();

        if ($prevDate->timestamp >= now()->timestamp) {
            $prev = Keyboard::inlineButton(
                [
                    'text' => '◀️',
                    'callback_data' => Callback::create('calendar_request', [$prevDate->year, $prevDate->month])
                ]
            );
        } else {
            $prev = Keyboard::inlineButton(['text' => ' ', 'callback_data' => 'noop']);
        }

        $nextDate = $date->copy();
        $nextDate->addMonth();
        $next = Keyboard::inlineButton(
            [
                'text' => '▶️',
                'callback_data' => Callback::create('calendar_request', [$nextDate->year, $nextDate->month])
            ]
        );

        $keyboard->row([$prev, $next]);

        $month = Keyboard::inlineButton([
            'text' => $date->isoFormat('MMMM'),
            'callback_data' => Callback::create('calendar_request_month', [$date->year, $date->month])
        ]);

        $year = Keyboard::inlineButton([
            'text' => $date->year,
            'callback_data' => 'noop'
        ]);

        $keyboard->row([$month, $year]);

        $daysHeader = [];
        $dayNames = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
        foreach ($dayNames as $dayName) {
            $daysHeader[] = Keyboard::inlineButton([
                'text' => $dayName,
                'callback_data' => 'noop'
            ]);
        }
        $keyboard->row($daysHeader);
    }

    private function createBody(Keyboard $keyboard, Carbon $date): void
    {
        $row = [];
        $daysInMonth = $date->daysInMonth;
        $startIso = $date->dayOfWeekIso;

        // Заполняем пустые ячейки до первого дня
        for ($i = 1; $i < $startIso; $i++) {
            $row[] = ['text' => ' ', 'callback_data' => 'ignore'];
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            // Добавить проверку, что дата не доступна
            $text = $day;

            // Если занято
            if (in_array($day, [12, 18, 21])) {
                $text = " ";
            }

            $callbackData = [
                $date->year,
                $date->month,
                $day,
            ];

            $row[] = [
                'text' => $text,
                'callback_data' => (string)(new Callback('calendar_request_day', $callbackData))
//                'callback_data' => (string)(new Callback('calendar_request_day', $callbackData))
            ];

            if (count($row) === 7) {
                $keyboard->row($row);
                $row = [];
            }
        }


        // Calendar days


//        foreach ($date->weeks as $week) {
//            $weekButtons = [];
//            foreach ($week as $day) {
//                $text = $day['day'];
//                $callbackData = 'noop';
//
//                if ($day['is_current_month']) {
//                    if ($day['is_past']) {
//                        $text = '❌';
//                    } elseif ($day['is_today']) {
//                        $text = $day['is_available'] ? "🟢{$day['day']}" : "❌{$day['day']}";
//                    } elseif ($day['is_available']) {
//                        $callbackData = json_encode([
//                            'action' => 'select_date',
//                            'date' => $day['date']->format('Y-m-d'),
//                            'year' => $calendarData['year'],
//                            'month' => $calendarData['month'],
//                        ]);
//                    } else {
//                        $text = "❌{$day['day']}";
//                    }
//                } else {
//                    $text = '⬜'; // Empty for other month days
//                }
//
//                $weekButtons[] = Keyboard::inlineButton([
//                    'text' => $text,
//                    'callback_data' => $callbackData
//                ]);
//            }
//            $keyboard->row(...$weekButtons);
//        }
    }
}
