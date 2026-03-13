<?php

namespace App\Models\v4\Client;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'hrv_baseline',
        'resting_hr',
        'sleep_minutes',
        'energy_pool_state',
        'capacity_points',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
