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

    public function __toString(): string
    {
        return sprintf('%s|%s', $this->action, implode('|', $this->data));
    }
}
