<?php

namespace App\Containers\Bot\Telegram\CallbackQuery\DTO;

use Carbon\Carbon;

/**
 * Кодирование даты для callback запроса
 */
class DateCallback extends AbstractCallback
{
    public ?int $year = null;
    public ?int $month = null;
    public ?int $day = null;
    public ?int $hour = null;
    public ?int $minute = null;

    /**
     * Создание описания callback запроса на основе даты
     *
     * @param string $action
     * @param Carbon $date
     * @return static
     */
    public static function createFromDate(string $action, Carbon $date): static
    {
        $callback = new static($action);
        $callback->year = $date->year;
        $callback->month = $date->month;
        $callback->day = $date->day;
        $callback->hour = $date->hour;
        $callback->minute = $date->minute;

        return $callback;
    }

    /**
     * Кодирование описания callback запроса на основе даты
     *
     * @param string $action
     * @param Carbon $date
     * @return string
     */
    public static function encodeFromDate(string $action, Carbon $date): string
    {
        return static::createFromDate($action, $date)->encode();
    }

    /**
     * Устанавливает текущую дату
     *
     * @param array $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->year = $data[0] ?? null;
        $this->month = $data[1] ?? null;
        $this->day = $data[2] ?? null;
        $this->hour = $data[3] ?? null;
        $this->minute = $data[4] ?? null;
    }

    /**
     * Предоставляет данные, для кодирования
     *
     * @return array
     */
    protected function getEncodeData(): array
    {
        return [
            $this->year,
            $this->month,
            $this->day,
            $this->hour,
            $this->minute
        ];
    }
}
