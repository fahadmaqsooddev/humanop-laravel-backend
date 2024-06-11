<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\DailyTip\DailyTip;

class TipRecord extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function createTip($id = null)
    {
        return self::create([
            'tip_id' => $id,
            'user_id' => Auth::user()['id']
        ]);
    }

    public static function getTipRecord()
    {
        $records = self::where('user_id', Auth::user()['id'])->pluck('tip_id');

        return $records;

    }

}
