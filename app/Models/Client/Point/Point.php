<?php

namespace App\Models\Client\Point;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function storePoint($data = null){
        self::create($data);
    }
    public static function userExists($user_id = null){
        return self::where('user_id',$user_id)->first();
    }
    public static function updatePoint($user_id = null,$data = null){
        return self::where('user_id',$user_id)->update($data);
    }
}
