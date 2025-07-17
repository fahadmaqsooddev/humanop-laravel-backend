<?php

namespace App\Models\Admin\StripeSetting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeSetting extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function getSingle()
    {
        return self::where('type', 1)->latest()->first();
    }

    public static function updateStripeAccount($data = null, $id = null)
    {
        $account = self::whereId($id)->first();

        $account->update($data);

        return $account;
    }
}
