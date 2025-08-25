<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Интеграции с сервисами
 */
class Integration extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'id',
        'service_id',
        'user_id',
        'token',
        'verified'
    ];

    protected $table = 'integrations';
    protected $keyType = 'string';

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'id', 'service_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
