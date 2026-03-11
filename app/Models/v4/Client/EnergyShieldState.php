<?php

namespace App\Models\v4\Client;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnergyShieldState extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'capacity_points',
        'shield_points',
        'shield_percent',
        'energy_pool_state',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
