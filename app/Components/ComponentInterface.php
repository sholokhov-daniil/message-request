<?php

namespace App\Components;

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

