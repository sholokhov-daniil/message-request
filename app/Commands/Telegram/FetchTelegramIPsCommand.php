<?php

namespace App\Commands\Telegram;

use App\Models\Webhook\WhiteIpWebhook;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchTelegramIPsCommand extends Command
{
    protected $signature = 'telegram:fetch-ips';
    protected $description = "Получение и запись белых IP адресов telegram из cidr.txt";

    public function handle(): int
    {
        $url = config('telegram.cidr');

        if (empty($url)) {
            $this->alert('Отсутствует адрес cidr.txt');
            return static::FAILURE;
        }

        $response = Http::get($url);

        if ($response->failed()) {
            $this->alert('Ошибка отправки запроса');
            return static::FAILURE;
        }

        $ips = explode(PHP_EOL, $response->body());
        WhiteIpWebhook::syncIps('telegram', $ips);

        return static::SUCCESS;
    }
}

