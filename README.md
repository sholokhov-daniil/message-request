# Регистрация нового бота
- Создать запись в таблице services
- Зарегистрировать webhook
- Зарегистрировать доступные команды

Регистрация webhook
```php
app('telegram')->setWebhook([
    'url' => env('TELEGRAM_WEBHOOK_URL')
]);
```

Регистрация команд
```php
app('telegram')->setMyCommands([
    'commands' => [
        ['command' => 'start', 'description' => 'Начало работы с ботом'],
        ['command' => 'help', 'description' => 'Помощь по командам'],
        ['command' => 'sign_up', 'description' => 'Записаться']
    ],
]);
```
