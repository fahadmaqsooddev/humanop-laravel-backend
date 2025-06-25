<?php

namespace App\Models\CLient\HumanOpPoints;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginStreaks extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }


    public static function getStreak($user = null)
    {
        return self::where('user_id', $user['id'])->first();
    }

    public static function startLoginStreak($user = null)
    {
        return self::create([
            'user_id' => $user['id'],
            'login_days' => 1,
        ]);
    }

    public static function updateLoginStreak($user = null)
    {
        $streak = self::where('user_id', $user['id'])->first();

        $streak->login_days += 1;

        $streak->save();

        return $streak;
    }
}
