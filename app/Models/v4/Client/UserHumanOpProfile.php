<?php

namespace App\Models\v4\Client;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHumanOpProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trait',
        'pilot_driver',
        'copilot_driver',
        'interval',
        'energy_pool_state',
        'preferences',
        'assessment_id'
    ];

    protected $casts = [
        'preferences' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSingleRecord($userId = null)
    {
        return self::where('user_id', $userId)->first();
    }

}
