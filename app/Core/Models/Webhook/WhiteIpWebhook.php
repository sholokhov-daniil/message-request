<?php

namespace App\Core\Models\Webhook;

use App\Core\Models\Messenger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhiteIpWebhook extends Model
{
    protected $table = 'secure_whitelist_ip_webhook';

    protected $fillable = [
        'id',
        'service_id',
        'ip',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Messenger::class, 'id', 'service_id');
    }

    /**
     * Синхронизация белого списка IP адресов сервисов
     *
     * @param int $serviceId
     * @param array $ips
     * @return void
     */
    public static function syncIps(int $serviceId, array $ips): void
    {
        $existingIps = self::query()
            ->where('service_id', $serviceId)
            ->pluck('ip')
            ->toArray();

        $ipsToDelete = array_diff($existingIps, $ips);
        $ipsToAdd = array_diff($ips, $existingIps);

        if (!empty($ipsToDelete)) {
            self::query()
                ->where('service_id', $serviceId)
                ->whereIn('ip', $ipsToDelete)
                ->delete();
        }

        foreach ($ipsToAdd as $ip) {
            self::query()->create([
                'service_id' => $serviceId,
                'ip' => $ip,
            ]);
        }
    }
}
