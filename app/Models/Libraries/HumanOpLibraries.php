<?php

namespace App\Models\Libraries;

use App\Helpers\Helpers;
use App\Models\Admin\HumanOpShopCategory\HumanOpShopTraits;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\Resources\ShopCategoryResource;
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

    public function shopItems()
    {
        return $this->belongsTo(ShopCategoryResource::class, 'item_id', 'id');
    }

    public function libraryItems()
    {
        return $this->belongsTo(LibraryResource::class, 'library_resource_id', 'id');
    }

    public static function getShopBuyItems()
    {

        $user = Helpers::getUser();

        return self::where('user_id', $user['id'])->whereNotNull('item_id')->with('shopItems')->get();

    }

    public static function getLibraryBuyItems()
    {

        $user = Helpers::getUser();

        return self::where('user_id', $user['id'])->whereNotNull('library_resource_id')->with('libraryItems')->get();

    }

    public static function getAllItems($userId = null)
    {
        return self::where('user_id', $userId)->whereNotNull('item_id')->get();
    }

    public static function getAllLibraries($userId = null)
    {
        return self::where('user_id', $userId)->whereNotNull('library_resource_id')->get();
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
