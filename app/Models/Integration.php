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

    protected $table = 'integrations';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'service_id',
        'user_id',
        'bot_id',
        'token',
        'verified'
    ];

    protected $casts = [
        'id' => 'string',
        'service_id' => 'string',
        'user_id' => 'integer',
        'bot_id' => 'string',
        'token' => 'string',
        'verified' => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Messenger::class, 'id', 'service_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
