<?php

namespace App\Service\Messengers\Telegram\DTO;

use JsonSerializable;

class Callback implements \Stringable
{
    public function __construct(
        public string $action,
        public array $data = []
    )
    {
    }

    public static function create(string $action, array $data = []): string
    {
        return sprintf('%s|%s', $action, implode('|', $data));
    }

    public function __toString(): string
    {
        return self::create($this->action, $this->data);
    }
}
