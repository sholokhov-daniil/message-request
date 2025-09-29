<?php

namespace App\Service\Messengers\Telegram\Components\Calendar;

use App\Components\ComponentInterface;
use App\Service\Messengers\Telegram\CallbackQuery\DTO\DateCallback;
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
                    'callback_data' => DateCallback::encodeFromDate('calendar_request', $prevDate)
                ]
            );
        } else {
            $prev = Keyboard::inlineButton(['text' => ' ', 'callback_data' => '']);
        }

        $nextDate = $date->copy();
        $nextDate->addMonth();
        $next = Keyboard::inlineButton(
            [
                'text' => '▶️',
                'callback_data' => DateCallback::encodeFromDate('calendar_request', $nextDate)
            ]
        );

        $keyboard->row([$prev, $next]);

        $month = Keyboard::inlineButton([
            'text' => $date->isoFormat('MMMM'),
            'callback_data' => DateCallback::encodeFromDate('calendar_request_month', $date)
        ]);

        $year = Keyboard::inlineButton([
            'text' => $date->year,
            'callback_data' => DateCallback::encodeFromDate('calendar_request_year', $date),
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
            $row[] = ['text' => ' ', 'callback_data' => ''];
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            // Добавить проверку, что дата не доступна
            $text = $day;

            // Если занято
            if (in_array($day, [12, 18, 21])) {
                $text = " ";
            }

            $nowDate = $date->copy();
            $nowDate->setDay($day);

            $row[] = [
                'text' => $text,
                'callback_data' => DateCallback::encodeFromDate('calendar_request_day', $nowDate)
            ];

            if (count($row) === 7) {
                $keyboard->row($row);
                $row = [];
            }
        }
    }
}
