<?php

namespace App\Models\CompatibilityReferenceKeys;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyPoolCompatibilityReferenceKeys extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function energyPoolCompatabilityCalculate($firstUserEnergyPool = null, $secondUserEnergyPool = null)
    {

        $ep1 = trim(strtolower($firstUserEnergyPool));

        $ep2 = trim(strtolower($secondUserEnergyPool));

        $energyPool =  self::where('first_reference_key' , $ep1)->where('second_reference_key', $ep2)->first('volume');

        return $energyPool['volume'];

    }
}
