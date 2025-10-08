<?php

namespace App\Http\Requests\Integration;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Валидация запроса на регистрацию бота сервиса
 */
class RegistrationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'service_id' => 'required|string|max:255',
            'token' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'Не указан сервис интеграции',
            'service_id.string' => 'Некорректный формат сервиса',
            'service_id.max' => 'Идентификатор сервиса не может быть длиннее :max символов',
            'token.required' => 'Не указан токен',
            'token.string' => 'Некорректный формат токена',
            'token.max' => 'Токен не может быть длиннее :max символов',
        ];
    }
}
