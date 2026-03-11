<?php

namespace App\Models\v4\Client;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_type',
        'recommended_protocol',
        'inputs_snapshot',
        'detected_at',
    ];

    protected $casts = [
        'inputs_snapshot' => 'array',
        'detected_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
