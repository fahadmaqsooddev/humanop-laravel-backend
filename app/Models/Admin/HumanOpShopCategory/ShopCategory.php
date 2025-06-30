<?php

namespace App\Models\Admin\HumanOpShopCategory;

use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\Resources\ShopCategoryResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    use HasFactory;


    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }


    // relations
    public function libraryResources()
    {

        return $this->hasMany(ShopCategoryResource::class, 'humanop_shop_category_id', 'id')->whereNotNull('humanop_shop_category_id');
    }


    // query

    public static function createShopeCategory($name)
    {
        self::create(['name' => $name]);

    }

    public static function deleteSingleCategory($id)
    {
        LibraryResource::deleteResourceOfCategory($id);
        self::whereId($id)->delete();
    }


    public static function categories()
    {

        return self::with('libraryResources')->get();
    }

    public static function resourceCategories()
    {

        return self::get('name');
    }

    public static function dropDownCategories()
    {

        return self::all();
    }
}
