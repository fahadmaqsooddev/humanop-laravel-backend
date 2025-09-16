<?php

namespace App\Models\Admin\Resources;

use App\Models\Admin\HumanOpItemsGridActivitiesLog;
use App\Models\Libraries\HumanOpLibraries;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Helpers\Helpers;
use App\Models\Admin\ResourceCategory\ResourceCategory;

class LibraryResource extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    protected $appends = ['photo_url', 'video_url', 'audio_url', 'thumbnail_url'];

    // relation
    public function libraryPermissions()
    {

        return $this->hasOne(PermissionResource::class, 'resource_id', 'id');
    }

    public function resourceCategory()
    {
        return $this->belongsTo(ResourceCategory::class, 'resource_category_id', 'id');
    }

    public function resourceTraits()
    {
        return $this->hasMany(HumanOpItemsGridActivitiesLog::class, 'resource_item_id');
    }


    // append
    public function getPhotoUrlAttribute()
    {
        if (empty($this->source_id) && empty($this->embed_link)) {
            return Helpers::getImage($this->upload_id, 'humanop_default_image.png');

        } else {

            return null;
        }

    }

    public function getThumbnailUrlAttribute()
    {
        if (!empty($this->thumbnail_id)) {

            return Helpers::getImage($this->thumbnail_id);

        } else {

            return null;
        }

    }

    public function getVideoUrlAttribute()
    {

        if (!empty($this->source_id)) {

            $client = new Client();

            $response = $client->request('GET', 'https://api.gumlet.com/v1/video/assets/' . $this->source_id, [
                'headers' => [
                    'Authorization' => 'Bearer gumlet_f330acf5449eaf2e84a63a2931a80023',
                    'accept' => 'application/json',
                ],
            ]);

            $response_body = json_decode($response->getBody()->getContents(), true);

            if (!empty($response_body) && in_array($response_body['status'], ['ready', 'queued'])) {

                return Helpers::getVideo($this->upload_id, 1, $response_body['output']['playback_url']);

            }

        } elseif (!empty($this->embed_link)) {
            return Helpers::getVideo($this->upload_id, 1, null, $this->embed_link);

        } else {

            return Helpers::getVideo($this->upload_id, 1, null);

        }

    }

    public function getAudioUrlAttribute()
    {

        return Helpers::getAudio($this->upload_id, 1);
    }


    // query
    public static function getResources()
    {
        return self::get();
    }

    public static function createResource($heading = null, $uploadId = null, $category_id = null, $description = null, $content = null, $link = null, $relevance = null, $thumbnailId = null)
    {
        $resource = self::create([
            'heading' => $heading,
            'slug' => Str::slug($heading),
            'upload_id' => $uploadId,
            'resource_category_id' => $category_id,
            'description' => $description,
            'content' => $content,
            'embed_link' => $link,
            'relevance' => $relevance,
            'thumbnail_id' => $thumbnailId
        ]);

        return $resource;
    }

    public static function updateResource($heading = null, $uploadId = null, $id = null, $category_id = null, $description = null, $content = null, $link = null, $relevance = null, $thumbnail_id = null)
    {

        self::whereId($id)->update([
            'heading' => $heading,
            'slug' => Str::slug($heading),
            'upload_id' => $uploadId,
            'resource_category_id' => $category_id,
            'description' => $description,
            'content' => $content,
            'source_id' => null,
            'source_url' => null,
            'embed_link' => $link,
            'relevance' => $relevance,
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
        return self::where('resource_category_id', $id)->delete();
    }

    public static function singleLibraryResource($resource_id)
    {

        return self::whereId($resource_id)->with('libraryPermissions')->first();
    }

    public static function resourcesForApi()
    {

        $user_plan = Helpers::getUser()->plan_name;

        $permission_id = $user_plan === 'Freemium' || $user_plan === 'Premium' ? $user_plan === 'Premium' ? 2 : 1 : 3;

        return self::whereHas('libraryPermissions', function ($q) use ($permission_id) {

            $q->whereIn('permission', [4, $permission_id]);

        })->get();

    }

    public static function updateCategory($current, $new)
    {
        self::where('resource_category_id', $current)->update(['resource_category_id' => $new]);
    }

    public static function latestLibraryResourcses()
    {
        $plan = Helpers::getUser()['plan_name'] ?? '';

        $permission = match ($plan) {
//            'Core' => 2,
            'Premium' => 3,
            default => 1,
        };

        $purchasedItemIds = HumanOpLibraries::getAllLibraries(Helpers::getUser()['id'])->pluck('library_resource_id')->toArray();

        $resource = self::whereHas('libraryPermissions', function ($q) use ($permission) {
            $q->where('permission', $permission);
        })
            ->with(['resourceCategory', 'libraryPermissions'])
            ->whereNotIn('id', $purchasedItemIds)
            ->latest()
            ->take(4)
            ->get();

        return $resource;
    }


    public static function resourceCategoriesForClient($searchType = null, $searchAccess = null, $searchRelevance = null)
    {
        $user = Helpers::getUser();
        $userId = $user['id'];
        $userPlan = $user['plan_name'];

        // Get all purchased item IDs
        $purchasedItemIds = HumanOpLibraries::getAllLibraries($userId)->pluck('library_resource_id')->toArray();

        // Build base query
        $query = self::query();

        // Exclude purchased items
        $query->whereNotIn('id', $purchasedItemIds);

        // Filter by relevance
        if (!empty($searchRelevance)) {
            $query->where('relevance', $searchRelevance);
        }

        // Filter by resource category (type)
        if (!empty($searchType)) {
            $query->whereHas('resourceCategory', function ($q) use ($searchType) {
                $q->where('name', 'LIKE', '%' . $searchType . '%');
            });
        }

        // Filter by access level
        if (!empty($searchAccess)) {
            $query->whereHas('libraryPermissions', function ($q) use ($searchAccess) {
                if ($searchAccess === 'free') {
                    $q->where('permission', 1);
                } elseif ($searchAccess === 'hp_look') {
                    $q->where('permission', 4);
                } else {
                    $q->whereIn('permission', [2, 3]);
                }
            });
        }

        // Determine permission level based on plan
        $permissionLevels = match ($userPlan) {
//            'Premium' => [3, 2, 1],
            'Premium' => [2, 1],
            default => [1], // Freemium or anything else
        };

        // Filter by permission levels (plan-based access)
        $query->whereHas('libraryPermissions', function ($q) use ($permissionLevels) {
            $q->whereIn('permission', $permissionLevels);
        });

        // Eager load relationships and order
        $query->with(['resourceCategory', 'libraryPermissions'])
            ->orderBy('created_at', 'desc');

        return $query->get();
    }

    public static function allResourceCategories()
    {

        $user = Helpers::getUser();

        $userPlan = $user['plan_name'];

        $query = self::query();

        $permissionLevels = match ($userPlan) {
            'Premium' => [2, 1],
//            'Core' => [2, 1],
            default => [1], // Freemium or anything else
        };

        $query->whereHas('libraryPermissions', function ($q) use ($permissionLevels) {
            $q->whereIn('permission', $permissionLevels);
        });

        $query->with(['resourceCategory', 'libraryPermissions'])
            ->orderBy('created_at', 'desc');

        return $query->get();
    }

}
