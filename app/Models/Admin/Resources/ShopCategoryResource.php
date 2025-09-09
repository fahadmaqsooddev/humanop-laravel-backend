<?php

namespace App\Models\Admin\Resources;

use App\Helpers\Helpers;
use App\Models\Admin\HumanOpItemsGridActivitiesLog;
use App\Models\Admin\HumanOpShopCategory\HumanOpShopTraits;
use App\Models\Admin\HumanOpShopCategory\ShopCategory;
use App\Models\Admin\ResourceCategory\ResourceCategory;
use App\Models\Assessment;
use App\Models\Libraries\HumanOpLibraries;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShopCategoryResource extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    protected $appends = ['document_url', 'video_url', 'audio_url','image_url', 'thumbnail_url'];

    // relation
    public function shopCategory()
    {
        return $this->belongsTo(ShopCategory::class, 'humanop_shop_category_id', 'id');
    }


//    relation
    public function resourceTraits()
    {
        return $this->hasMany(HumanOpItemsGridActivitiesLog::class, 'shop_item_id');
    }


    // append
    public function getDocumentUrlAttribute()
    {
        return Helpers::getDocument($this->document_id, 1, null);
    }

    public function getVideoUrlAttribute()
    {
        return Helpers::getVideo($this->video_id, 1, null);
    }

    public function getAudioUrlAttribute()
    {
        return Helpers::getAudio($this->audio_id, 1);
    }

    public function getImageUrlAttribute()
    {
        if (!empty($this->image_id)){

            return Helpers::getImage($this->image_id, 1);

        }else{

            return null;
        }
    }

    public function getThumbnailUrlAttribute()
    {
        if (!empty($this->thumbnail_id)){

            return Helpers::getImage($this->thumbnail_id);

        }else{

            return null;
        }
    }


    // query
    public static function getResources()
    {
        return self::with('shopCategory', 'resourceTraits')->get();
    }

    public static function getNotPurchasedShopResources($userId = null)
    {
        $shops = self::query();

        $purchasedItemIds = HumanOpLibraries::getAllItems($userId)->pluck('item_id')->toArray();

        return $shops->whereNotIn('id', $purchasedItemIds)->with(['shopCategory', 'resourceTraits'])->get();

    }

    public static function createShopResource($heading = null, $category_id = null, $price = null, $video_id = null, $audio_id = null, $document_id = null, $image_id = null, $point = null,$description = null, $thumbnail_id = null)
    {
        $resource = self::create([
            'heading' => $heading,
            'description' => $description,
            'slug' => Str::slug($heading),
            'humanop_shop_category_id' => $category_id,
            'price' => $price,
            'video_id' => $video_id,
            'audio_id' => $audio_id,
            'document_id' => $document_id,
            'image_id' => $image_id,
            'point' => $point,
            'thumbnail_id' => $thumbnail_id
        ]);

        return $resource;
    }

    public static function updateResource($heading = null, $id = null, $category_id = null, $price = null, $video_id = null, $audio_id = null, $document_id = null, $image_id = null, $point = null,$description = null, $thumbnail_id = null)
    {

        self::whereId($id)->update([
            'heading' => $heading,
            'description' => $description,
            'slug' => Str::slug($heading),
            'humanop_shop_category_id' => $category_id,
            'price' => $price,
            'video_id' => $video_id,
            'audio_id' => $audio_id,
            'document_id' => $document_id,
            'image_id' => $image_id,
            'point' => $point,
            'thumbnail_id' => $thumbnail_id

        ]);

        return self::singleLibraryResource($id);

    }

    public static function deleteResource($id = null)
    {
        return self::whereId($id)->delete();
    }

    public static function deleteResourceOfCategory($id = null)
    {
        return self::where('humanop_shop_category_id', $id)->delete();
    }

    public static function singleLibraryResource($resource_id)
    {

        return self::whereId($resource_id)->with('resourceTraits')->first();
    }

    public static function resourcesForApi()
    {

        $user_plan = Helpers::getUser()->plan_name;

        $permission_id = $user_plan === 'Freemium' || $user_plan === 'Core' ? $user_plan === 'Core' ? 2 : 1 : 3;

        return self::whereHas('libraryPermissions', function ($q) use ($permission_id) {

            $q->whereIn('permission', [4, $permission_id]);

        })->get();

    }

    public static function updateCategory($current, $new)
    {
        self::where('humanop_shop_category_id', $current)->update(['humanop_shop_category_id' => $new]);
    }

    public static function latestLibraryResourcses()
    {
        return self::with('resourceCategory')
            ->latest()
            ->take(3)
            ->get();
    }

    public static function resourceCategoriesForClient($searchType = null, $searchAccess = null, $searchRelevance = null)
    {
        $resources = self::query();

        if (!empty($searchRelevance)) {
            $resources->where('relevance', $searchRelevance);
        }

        if (!empty($searchType)) {
            $resources->whereHas('resourceCategory', function ($q) use ($searchType) {
                $q->where('name', 'LIKE', '%' . $searchType . '%');
            });
        }

        if (!empty($searchAccess)) {
            $resources->whereHas('libraryPermissions', function ($q) use ($searchAccess) {
                if ($searchAccess === 'free') {
                    $q->where('permission', 1);
                } elseif ($searchAccess === 'hp_look') {
                    $q->where('permission', 4);
                } else {
                    $q->whereIn('permission', [2, 3]);
                }
            });
        }

        $resources->with('resourceCategory', 'libraryPermissions')->orderBy('created_at', 'desc');

        return $resources->get();
    }

    public static function suggestedItems($userId = null)
    {

        $purchasedItemIds = HumanOpLibraries::getAllItems($userId)->pluck('item_id');

        $getAssessment = Assessment::getLatestAssessment($userId);

        $highlightedStyles = Assessment::highLightStyle($getAssessment);

        $matchingItems = self::whereNotIn('id', $purchasedItemIds)

            ->whereHas('resourceTraits', function ($query) use ($highlightedStyles) {

                $query->whereIn('grid_name', $highlightedStyles);

            })

            ->with(['resourceTraits' => function ($query) use ($highlightedStyles) {

                $query->whereIn('grid_name', $highlightedStyles);

            }])

            ->get();

        if ($matchingItems->count() >= 3) {

            return $matchingItems->take(3);

        }

        $needed = 3 - $matchingItems->count();

        $additionalItems = self::whereNotIn('id', $matchingItems->pluck('id')->merge($purchasedItemIds))
            ->inRandomOrder()
            ->with('resourceTraits')
            ->take($needed)
            ->get();

        return $matchingItems->merge($additionalItems);

    }

}
