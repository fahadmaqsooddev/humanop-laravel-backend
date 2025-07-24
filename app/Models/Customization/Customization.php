<?php

namespace App\Models\Customization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customization extends Model
{
    use HasFactory;

    CONST HP_TO_HAI_CREDITS = "Human Op points to HAi Credits";

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    // query
    public static function oneHaiCreditDetail(){

        return self::where('detail', self::HP_TO_HAI_CREDITS)->first()?->points;
    }
}
