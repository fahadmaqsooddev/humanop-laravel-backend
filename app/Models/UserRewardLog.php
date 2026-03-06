<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Reward\Reward;
use Illuminate\Support\Carbon;
class UserRewardLog extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        parent::__construct($attributes);
    }

    /**
     * Create a new reward log entry
     *
     * @param int $userId
     * @param string $type
     * @param int $points
     * @return self
     */


    public static function createLog(int $userId, Reward|string $type, int $points): self
    {
        return self::create([
            'user_id' => $userId,
            'type'    => $type instanceof Reward ? $type->value : $type,
            'points'  => $points,
        ]);
    }

    public static function getLast24HoursLogs(int $userId)
    {
        $since = Carbon::now()->subDay();
        return self::where('user_id', $userId)
            ->where('created_at', '>=', $since)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
