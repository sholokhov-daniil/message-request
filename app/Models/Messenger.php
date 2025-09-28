<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Сервисы с которыми производится интеграция
 */
class Messenger extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = ['id', 'name', 'active'];

    protected $primaryKey = 'id';

    protected $keyType = 'string';
}
