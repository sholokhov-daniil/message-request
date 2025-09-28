<?php

namespace App\Service\Messengers\Telegram\CallbackQuery\Calendar\Callback;

use App\Service\Messengers\Telegram\CallbackQuery\Http\AbstractQuery;

class DayCallback extends AbstractQuery
{
    /**
     * @return int
     */
    public function getYear(): int
    {
        return (int)$this->collection->get('year', 0);
    }

    /**
     * @param int $year
     * @return $this
     */
    public function setYear(int $year): static
    {
        $this->collection->put('year', $year);
        return $this;
    }

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return (int)$this->collection->get('month', 0);
    }

    /**
     * @param int $month
     * @return $this
     */
    public function setMonth(int $month): static
    {
        $this->collection->put('month', $month);
        return $this;
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return (int)$this->collection->get('day', 0);
    }

    /**
     * @param int $day
     * @return $this
     */
    public function setDay(int $day): static
    {
        $this->collection->put('day', $day);
        return $this;
    }

    /**
     * @param string $data
     * @return array
     */
    protected function decodeData(string $data): array
    {
        list($year, $month, $day) = explode(self::SEPARATOR, $data);

        return compact('year', 'month', 'day');
    }

    /**
     * @return string
     */
    protected function encodeData(): string
    {
        return implode(
            self::SEPARATOR,
            [
                $this->getYear(),
                $this->getMonth(),
                $this->getDay(),
            ]
        );
    }
}
