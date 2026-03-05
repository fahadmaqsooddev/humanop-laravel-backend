<?php

namespace App\Models\v4\Client\DailySync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DailySyncSession extends Model
{
    use HasFactory;

    const COMPLETED = 1;

    const NOT_COMPLETED = 0;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    protected $casts = [
        'completed_at' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(DailySyncResponse::class, 'session_id');
    }

    public static function createSessions($user = null)
    {
        return self::create([
            'user_id' => $user->id,
            'completed_at' => self::NOT_COMPLETED,
        ]);
    }
}
