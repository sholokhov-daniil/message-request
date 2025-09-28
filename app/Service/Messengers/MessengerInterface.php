<?php

namespace App\Service\Messengers;

use App\Components\ComponentInterface;

interface MessengerInterface
{
    public function getId(): string;

    public function getComponent(string $name): ?ComponentInterface;
}
