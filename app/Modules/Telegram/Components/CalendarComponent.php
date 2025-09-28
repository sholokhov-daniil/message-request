<?php

namespace App\Modules\Telegram\Components;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Telegram\Bot\Keyboard\Keyboard;

class CalendarComponent
{
    public function render(Collection $data)
    {
    }

    private function generate(int $year, int $month)
    {
        $date = Carbon::create($year, $month);
        $keyboard = Keyboard::make()->inline();

        // Заголовок с навигацией
        $prevMonth = $date->copy()->subMonth();
        $nextMonth = $date->copy()->addMonth();

        $keyboard->row([
            Keyboard::inlineButton([
                'text' => '◀️',
                'callback_data' => "cal_prev_{$prevMonth->format('Y-m')}"
            ]),

            Keyboard::inlineButton([
                'text' => $date->format('F Y'),
                'callback_data' => "cal_ignore"
            ]),

            Keyboard::inlineButton([
                'text' => '▶️',
                'callback_data' => "cal_next_{$nextMonth->format('Y-m')}"
            ])
        ]);

        // Дни недели
        $weekDays = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
        $dayButtons = array_map(function($day) {
            return Keyboard::inlineButton([
                'text' => $day,
                'callback_data' => "cal_ignore"
            ]);
        }, $weekDays);
        $keyboard->row($dayButtons);

        // Генерация дней
        $this->generateCalendarDays($keyboard, $date, $selectedDates, $disabledDates, $minDate, $maxDate);

        return $keyboard;
    }

    private function generateCalendarDays(Keyboard $keyboard, Carbon $date, array $selectedDates, array $disabledDates, $minDate, $maxDate): void
    {
        $firstDay = $date->copy()->startOfMonth()->dayOfWeekIso;
        $daysInMonth = $date->daysInMonth;

        $currentRow = [];

        // Пустые ячейки в начале
        for ($i = 1; $i < $firstDay; $i++) {
            $currentRow[] = Keyboard::inlineButton([
                'text' => ' ',
                'callback_data' => "cal_ignore_{$this->getId()}"
            ]);
        }

        // Дни месяца
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dayDate = $date->copy()->day($day);
            $dateStr = $dayDate->format('Y-m-d');

            $isDisabled = $this->isDateDisabled($dayDate, $disabledDates, $minDate, $maxDate);
            $isSelected = in_array($dateStr, $selectedDates);

            $text = $day;
            if ($isSelected) {
                $text = "✅ $day";
            } elseif ($isDisabled) {
                $text = "❌ $day";
            }

            $callbackData = $isDisabled
                ? "cal_ignore"
                : "cal_select_{$dateStr}";

            $currentRow[] = Keyboard::inlineButton([
                'text' => $text,
                'callback_data' => $callbackData
            ]);

            if (count($currentRow) === 7) {
                $keyboard->row($currentRow);
                $currentRow = [];
            }
        }

        // Завершаем последнюю строку
        if (!empty($currentRow)) {
            while (count($currentRow) < 7) {
                $currentRow[] = Keyboard::inlineButton([
                    'text' => ' ',
                    'callback_data' => "cal_ignore"
                ]);
            }
            $keyboard->row($currentRow);
        }
    }

    private function isDateDisabled(Carbon $date, array $disabledDates, $minDate, $maxDate): bool
    {
        // Проверка минимальной и максимальной даты
        if ($minDate && $date->lt(Carbon::parse($minDate))) {
            return true;
        }

        if ($maxDate && $date->gt(Carbon::parse($maxDate))) {
            return true;
        }

        // Проверка отключенных дат
        if (in_array($date->format('Y-m-d'), $disabledDates)) {
            return true;
        }

        // Другие условия отключения (выходные, прошедшие даты и т.д.)
        $disablePastDates = $this->getData('disable_past_dates', true);
        if ($disablePastDates && $date->isPast()) {
            return true;
        }

        $disableWeekends = $this->getData('disable_weekends', false);
        if ($disableWeekends && $date->isWeekend()) {
            return true;
        }

        return false;
    }

    private function handlePrevMonth(string $dateStr): array
    {
        [$year, $month] = explode('-', $dateStr);
        $this->setData(['year' => (int)$year, 'month' => (int)$month]);

        return [
            'action' => 'update_calendar',
            'component' => $this
        ];
    }

    private function handleNextMonth(string $dateStr): array
    {
        [$year, $month] = explode('-', $dateStr);
        $this->setData(['year' => (int)$year, 'month' => (int)$month]);

        return [
            'action' => 'update_calendar',
            'component' => $this
        ];
    }

    private function handleDateSelect(string $dateStr): array
    {
        return [
            'action' => 'date_selected',
            'selected_date' => $dateStr,
        ];
    }
}
