<?php

namespace App\Models\B2B;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessStrategies extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public function businessSubStrategies()
    {

        return $this->hasMany(BusinessSubStrategies::class, 'business_strategy_id', 'id');
    }

    public static function allBusinessStrategies($searchBusinessName = null)
    {
        $business = self::query();

        if (!empty($searchBusinessName))
        {
            $business->where(function ($query) use ($searchBusinessName) {
                $query->where('name', 'like', '%'.$searchBusinessName.'%');
            });
        }

        return $business->get();
    }


    public static function storeStratergy($stratergy = null)
    {

        return self::create(['name' => $stratergy]);
    }

    }
