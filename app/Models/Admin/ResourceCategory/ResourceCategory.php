<?php

namespace App\Models\Admin\ResourceCategory;

use App\Models\Admin\Resources\LibraryResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceCategory extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }


    // relations
    public function libraryResources(){

        return $this->hasMany(LibraryResource::class,'resource_category_id','id')->whereNotNull('resource_category_id');
    }


    // query

    public static function createCategory($name){

        self::create(['name' => $name]);

    }

    public static function categories(){

        return self::has('libraryResources')->with('libraryResources')->get();
    }

    public static function dropDownCategories(){

        return self::all();
    }
}
