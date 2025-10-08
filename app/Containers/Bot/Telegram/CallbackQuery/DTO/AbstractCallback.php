<?php

namespace App\Containers\Bot\Telegram\CallbackQuery\DTO;

use Stringable;

/**
 * Базовый класс кодирования данных callback запроса
 */
abstract class AbstractCallback implements Stringable
{
    /**
     * Разделитель данных в закодированной строке
     */
    public const string SPLIT = "|";

    /**
     * Обработчик callback метода
     *
     * @var string
     */
    public readonly string $action;

    /**
     * Указание массива данных используемых callback методом
     *
     * @param array $data
     * @return void
     */
    abstract public function setData(array $data): void;

    /**
     * Предоставляет набор данных, для кодирования
     *
     * @return array
     */
    abstract protected function getEncodeData(): array;

    public function __construct(string $action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->encode();
    }

    /**
     * Упрощенное создание закодированной строки
     *
     * @param string $action
     * @param array $data
     * @return string
     */
    public static function create(string $action, array $data): string
    {
        $callback = new static($action);
        $callback->setData($data);

        return $callback->encode();
    }

    /**
     * Преобразовывает закодированную строку в описание текущего callback метода
     *
     * @param string $text
     * @return static
     */
    public static function decode(string $text): static
    {
        list($action, $stringData) = explode(self::SPLIT, $text, 2);
        $data = explode(self::SPLIT, $stringData);

        $callback = new static($action);
        $callback->setData($data);

        return $callback;
    }

    /**
     * Производит превращение DTO объекта в закодированную строку
     *
     * @return string
     */
    public function encode(): string
    {
        return $this->action . self::SPLIT . implode(self::SPLIT, $this->getEncodeData());
    }
}
