<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Интеграции с сервисами
 *
 * @property string $id
 * @property string $service_id
 * @property int $user_id
 * @property string $token
 * @property bool $verified
 */
class Bot extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'bots';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'service_id',
        'user_id',
        'token',
        'verified'
    ];

    protected $casts = [
        'id' => 'string',
        'service_id' => 'string',
        'user_id' => 'integer',
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
