<?php

namespace App\Models\B2B;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSubStrategies extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function getSubStrategies($businessId = null,$name=null)
    {
        $query = self::where('business_strategy_id', $businessId);

        if (!empty($name)) {
            $query->where(function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }

        $data = $query->get();
        return $data;

    }


    public static function storeSubStratergy($stratergyid = null, $substratergy = null)
    {
        return self::create([
            'business_strategy_id' => $stratergyid,
            'name' => $substratergy,
        ]);
    }
}
