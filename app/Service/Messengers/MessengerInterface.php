<?php

namespace App\Service\Messengers;

use App\Core\UI\Components\ComponentInterface;

interface MessengerInterface
{
    public function getId(): string;

    public function getComponent(string $name): ?ComponentInterface;
}
