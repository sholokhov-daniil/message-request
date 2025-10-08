<?php

namespace App\Core\Response;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HttpResponse extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->isSuccess(),
            'data' => (array)($this->resource['data'] ?? []),
            'errors' => (array)($this->resource['errors'] ?? [])
        ];
    }

    /**
     * Проверка наличия ошибок
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return empty($this->resource['errors']);
    }

    /**
     * Указание пользовательских данных результата работы контроллера
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data): static
    {
        $this['data'] = $data;
        return $this;
    }

    /**
     * Добавление ошибки в http результат
     *
     * @param string $message
     * @param int $code
     * @param array $context
     * @return $this
     */
    public function addError(string $message, int $code = 0, array $context = []): static
    {
        if (!isset($this['errors'])) {
            $this->resource['errors'] = [];
        }

        $this->resource['errors'][] = [
            'message' => $message,
            'code' => $code,
            'context' => $context,
        ];

        return $this;
    }
}
