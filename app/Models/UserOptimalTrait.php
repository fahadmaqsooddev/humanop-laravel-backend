<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOptimalTrait extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getOptimalTrait($userId = null)
    {
        return self::where('user_id', $userId)->first();
    }

    public static function createUserOptimalTrait($trait = null, $userId = null, $status = null)
    {
        return self::create([
            'user_id' => $userId,
            'optimal_trait' => $trait,
            'status' => $status,
        ]);
    }

    public static function updateUserOptimalTrait($trait = null, $userId = null, $status = null)
    {
        $userOptimalTrait = self::where('user_id', $userId)->first();

        $userOptimalTrait->update([
            'optimal_trait' => $trait,
            'status' => $status,
        ]);

        return $userOptimalTrait;
    }
}
