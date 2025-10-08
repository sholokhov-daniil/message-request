<?php

namespace App\Core\UI\Components;

interface ComponentInterface
{
    /**
     * Генерация данных компонента
     *
     * @param mixed $data
     * @return array
     */
    public function build(mixed $data): array;
}

