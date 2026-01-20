<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helpers;
use Carbon\Carbon;

class HotSpot extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        parent::__construct($attributes);
    }

    public static function updateHotspot(array $data): bool
    {
        return self::where('id', $data['id'])->update([
            'hotspot' => $data['hotspot'],
            'name'    => $data['name'],
        ]);
    }
}
