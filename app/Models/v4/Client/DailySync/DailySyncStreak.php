<?php

namespace App\Models\v4\Client\DailySync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySyncStreak extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function getUserDailySyncStreak($userId = null)
    {
        return self::where('user_id', $userId)->value('streak');
    }

    public static function createOrIncrement(int $userId): void
    {

        $record = self::firstOrCreate(
            ['user_id' => $userId],
            ['streak' => 0]
        );

        $record->increment('streak');

    }

}
