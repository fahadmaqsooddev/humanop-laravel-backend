<?php

namespace App\Models\CompatibilityReferenceKeys;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverCompatibilityReferenceKeys extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function driverCompatabilityCalculate($firstDriver = null, $secondDriver = null)
    {

        $driver1 = strtolower($firstDriver);

        $driver2 = strtolower($secondDriver);

        $driver =  self::where('first_reference_key' , $driver1)->where('second_reference_key', $driver2)->first('volume');

        return $driver['volume'];

    }

}
