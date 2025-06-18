<?php

namespace App\Models\Admin\ResourceCategory;

use App\Helpers\Helpers;
use App\Models\Admin\Resources\LibraryResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceCategory extends Model
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

        return $this->hasMany(LibraryResource::class, 'resource_category_id', 'id')->whereNotNull('resource_category_id');
    }


    // query

    public static function createCategory($name)
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

    public static function dropDownCategories()
    {

        return self::all();
    }

    public static function resourceCategoriesForClient($searchType = null, $searchAccess = null, $searchRelevance = null)
    {
        $resources = self::query();

        // Filter by searchType (matching name)
        if (!empty($searchType)) {
            $resources->where(function ($q) use ($searchType) {
                $q->where('name', 'LIKE', '%' . $searchType . '%');
            });
        }

        // Filter by relevance
        if (!empty($searchRelevance)) {
            $resources->whereHas('libraryResources', function ($q) use ($searchRelevance) {
                $q->where('relevance', $searchRelevance);
            });
        }

        // Filter by access level
        if (!empty($searchAccess)) {
            $resources->whereHas('libraryResources', function ($q) use ($searchAccess) {
                $q->whereHas('libraryPermissions', function ($q2) use ($searchAccess) {
                    if ($searchAccess == 'free') {
                        $q2->where('permission', 1);
                    } elseif ($searchAccess == 'hp_look') {
                        $q2->where('permission', 4);
                    } else {
                        $q2->whereIn('permission', [2, 3]);
                    }
                });
            });
        }

        // Always eager load the relationships
        $resources->with('libraryResources.libraryPermissions');

        return $resources->get();
    }

}
