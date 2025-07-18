<?php

namespace App\Models\Libraries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanOpLibraries extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getItem($item_id = null, $user_id = null,$type=null)
    {
        if($type==1){
            return self::where('item_id', $item_id)->where('user_id', $user_id)->where('type',$type)->first();
        }else{
            return self::where('library_resource_id', $item_id)->where('user_id', $user_id)->where('type',$type)->first();

        }
    }

    public static function getAllItems($userId = null)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function addItem($user_id = null, $item_id = null,$type = null)
    {

        if($type==1){
            return self::create([
                'user_id' => $user_id,
                'item_id' => $item_id,
                'type' => $type,
            ]);
        }else{
            return self::create([
                'user_id' => $user_id,
                'library_resource_id' => $item_id,
                'type' => $type,
            ]);
        }


    }

}
