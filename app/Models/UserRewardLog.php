<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Reward\Reward;
use Illuminate\Support\Carbon;
use App\Helpers\Helpers;

class UserRewardLog extends Model
{
    use HasFactory;

    protected $appends = ['type_label'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        parent::__construct($attributes);
    }


    public function getTypeLabelAttribute(): ?string
    {
        return Reward::tryFrom($this->type)?->label();
    }

    public static function createLog(int $userId, Reward|string $type, int $points): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type instanceof Reward ? $type->value : $type,
            'points' => $points,
        ]);
    }

    public static function getLast24HoursLogs(int $userId = null, $request = null)
    {

        $since = Carbon::now()->subDay();

        $rewardLogs =  self::where('user_id', $userId)
            ->where('created_at', '>=', $since)
            ->orderBy('created_at', 'desc')
            ->select(['type', 'points', 'created_at']);

        return Helpers::pagination($rewardLogs, $request->input('pagination'), $request->input('per_page'));

    }

}
