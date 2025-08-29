<?php

namespace App\Models\CompatibilityReferenceKeys;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraitCompatibilityPolarity extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getPolarity($firstUserTraits = null, $secondUserTraits = null)
    {

        $traits = [
            'first' => array_keys($firstUserTraits)[0] == array_keys($secondUserTraits)[0] ? 'equal' : array_keys($secondUserTraits)[0],
            'second' => array_keys($firstUserTraits)[1] == array_keys($secondUserTraits)[1] ? 'equal' : array_keys($secondUserTraits)[1],
            'third' => array_keys($firstUserTraits)[2] == array_keys($secondUserTraits)[2] ? 'equal' : array_keys($secondUserTraits)[2],
        ];

        $polarity = [];

        foreach ($traits as $key => $trait) {

            $record = self::where('reference_key', $trait)->first();

            $polarity[] = $record ? $record['volume'] : null;
        }

        return $polarity;
    }

}
