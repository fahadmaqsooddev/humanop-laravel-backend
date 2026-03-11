<?php

namespace App\Models\v4\Client;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiometricSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'metric',
        'value',
        'recorded_at',
        'source',
        'dedupe_key',
        'metadata',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
