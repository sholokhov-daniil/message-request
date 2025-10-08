<?php

namespace App\Containers\Bot\Telegram\CallbackQuery\Http;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use Stringable;
use Telegram\Bot\Objects\CallbackQuery;

abstract class AbstractQuery implements ContainerInterface, Stringable
{
    public const string SEPARATOR = '|';

    protected readonly string $action;
    protected readonly Collection $collection;

    abstract protected function decodeData(string $data): array;

    abstract protected function encodeData(): string;

    public function __construct(CallbackQuery $query)
    {
        $this->collection = new Collection;
        $this->decode($query);
    }

    public function __toString()
    {
        return $this->action . self::SEPARATOR . $this->encodeData();
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function get(string $id): mixed
    {
        return $this->collection->get($id);
    }

    public function has(string $id): bool
    {
        return $this->collection->has($id);
    }

    private function decode(CallbackQuery $query): void
    {
        list($action, $data) = explode(self::SEPARATOR, $query->data);

        $this->action = $action;
        $data = $this->decodeData($data);

        foreach ($data as $key => $value) {
            $this->collection->put($key, $value);
        }
    }
}
