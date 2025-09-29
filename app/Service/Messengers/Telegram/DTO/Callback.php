<?php

namespace App\Service\Messengers\Telegram\DTO;

use Stringable;
use Illuminate\Support\Collection;

class Callback implements Stringable
{
    public const string SPLIT = "|";

    public string $action;
    public readonly Collection $collection;

    public function __construct(string $action, array $data = [])
    {
        $this->action = $action;
        $this->collection = new Collection($data);
    }

    public static function create(string $action, array $data = []): string
    {
        return $action . self::SPLIT . implode(self::SPLIT, $data);
    }

    public function __toString(): string
    {
        return self::create($this->action, $this->collection->toArray());
    }
}
