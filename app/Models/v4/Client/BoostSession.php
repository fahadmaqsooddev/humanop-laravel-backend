<?php

namespace App\Models\v4\Client;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoostSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'protocol_type',
        'started_at',
        'ended_at',
        'hr_before',
        'hr_after',
        'hrv_before',
        'hrv_after',
        'q_physio',
        'energy_points_added',
        'replenishment_percent',
        'trait_modifier_key',
        'driver_modifier_key',
        'trait_modifier_value',
        'driver_modifier_value',
        'coherence_achieved',
        'metadata',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'coherence_achieved' => 'boolean',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
