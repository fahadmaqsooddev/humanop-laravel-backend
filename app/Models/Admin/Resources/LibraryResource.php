<?php

namespace App\Models\Admin\Resources;

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

    protected $appends = ['photo_url', 'video_url', 'audio_url'];

    // relation
    public function libraryPermissions()
    {

        return $this->hasOne(PermissionResource::class, 'resource_id', 'id');
    }

    public function resourceCategory()
    {
        return $this->belongsTo(ResourceCategory::class, 'resource_category_id', 'id');
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

    public static function createResource($heading = null, $uploadId = null, $category_id = null, $description = null, $content = null, $link = null, $relevance = null)
    {
        $resource = self::create([
            'heading' => $heading,
            'slug' => Str::slug($heading),
            'upload_id' => $uploadId,
            'resource_category_id' => $category_id,
            'description' => $description,
            'content' => $content,
            'embed_link' => $link,
            'relevance' => $relevance
        ]);

        return $resource;
    }

    public static function updateResource($heading = null, $uploadId = null, $id = null, $category_id = null, $description = null, $content = null, $link = null, $relevance = null)
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
            'relevance' => $relevance
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

        return self::whereId($resource_id)->with('libraryPermissions')->first()->toArray();
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
        self::where('resource_category_id', $current)->update(['resource_category_id' => $new]);
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

        // Filter by relevance in libraryResources
        if (!empty($searchRelevance)) {
            $resources->where('relevance', $searchRelevance);
        }

        // Filter by name in resourceCategory
        if (!empty($searchType)) {
            $resources->whereHas('resourceCategory', function ($q) use ($searchType) {
                $q->where('name', 'LIKE', '%' . $searchType . '%');
            });
        }

        // Filter by permission level in libraryPermissions
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
        $plan=Helpers::getUser()['plan_name'];
        if($plan=='Premium'){
            $searchType=3;
        }elseif ($plan=='Core'){
            $searchType=2;
        }else{
            $searchType=1;
        }

        $resources->with(['resourceCategory', 'libraryPermissions'])
            ->whereHas('libraryPermissions',function ($q) use ($searchType) {
                $q->where('permission', $searchType);
            })
            ->orderBy('created_at', 'desc');

        return $resources->get();
    }

}
