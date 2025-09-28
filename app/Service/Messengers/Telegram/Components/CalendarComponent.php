<?php

namespace App\Service\Messengers\Telegram\Components;

use App\Components\ComponentInterface;
use Carbon\Carbon;

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

        $daysInMonth  = $date->daysInMonth;
        $startIso     = $date->dayOfWeekIso;
        $year = $date->year;
        $month = $date->month;

//        $keyboard = [[
//            ['text' => '<', 'callback_data' => "prev:{$year}:{$month}"],
//            ['text' => $date->translatedFormat('F Y'), 'callback_data' => 'ignore'],
//            ['text' => '>', 'callback_data' => serialize([
//                'controller' => 'calendar',
//                'actions' => 'next',
//                'parameters' => [
//                    'year' => 2025,
//                    'month' => 9
//                ]
//            ])],
//        ]];
//
//        // Заголовок дней недели
//        $weekDaysRow = [];
//        foreach (['Пн','Вт','Ср','Чт','Пт','Сб','Вс'] as $wd) {
//            $weekDaysRow[] = ['text' => $wd, 'callback_data' => 'ignore'];
//        }
//        $keyboard[] = $weekDaysRow;
//
//
//        // Формируем дни месяца
//        $row = [];
//        // Заполняем пустые ячейки до первого дня
//        for ($i = 1; $i < $startIso; $i++) {
//            $row[] = ['text' => ' ', 'callback_data' => 'ignore'];
//        }
//        for ($day = 1; $day <= $daysInMonth; $day++) {
//            $text = $day === $selected
//                ? "🌞 {$day}"
//                : (string)$day;
//            $row[] = [
//                'text'          => $text,
//                'callback_data' => "pick:{$year}:{$month}:{$day}",
//            ];
//            if (count($row) === 7) {
//                $keyboard[] = $row;
//                $row = [];
//            }
//        }
//        // Заполняем пустые в конце месяца
//        if ($row) {
//            while (count($row) < 7) {
//                $row[] = ['text' => ' ', 'callback_data' => 'ignore'];
//            }
//            $keyboard[] = $row;
//        }
//
//        return ['inline_keyboard' => $keyboard];
//


        $keyboard = ['inline_keyboard' => []];
        $row = [];

        $daysInMonth = $data['now']->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dayText = (string)($day === 3 ? '✖' . $day : $day);

            $row[] = [
                'text' => $dayText,
                'callback_data' => 'date_' . $day
            ];

            // Каждые 7 дней создаем новый ряд (неделя)
            if ($day % 7 === 0 || $day === $daysInMonth) {
                $keyboard['inline_keyboard'][] = $row;
                $row = [];
            }
        }

        return $keyboard;
    }
}
