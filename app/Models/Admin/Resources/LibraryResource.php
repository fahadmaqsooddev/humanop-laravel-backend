<?php

namespace App\Models\Admin\Resources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Helpers\Helpers;

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

    protected $appends = ['photo_url','video_url','audio_url'];

    // relation
    public function libraryPermissions(){

        return $this->hasMany(PermissionResource::class,'resource_id','id');
    }


    // append
    public function getPhotoUrlAttribute(){

        return Helpers::getImage($this->upload_id,'profile_pic.png');
    }

    public function getVideoUrlAttribute(){

        return Helpers::getVideo($this->upload_id,1);
    }

    public function getAudioUrlAttribute(){

        return Helpers::getAudio($this->upload_id,1);
    }


    // query
    public static function getResources()
    {
        return self::get();
    }

    public static function createResource($heading = null, $uploadId = null, $category_id = null, $description = null, $content = null)
    {
        $resource =  self::create([
            'heading' => $heading,
            'slug' => Str::slug($heading),
            'upload_id' => $uploadId,
            'resource_category_id' => $category_id,
            'description' => $description,
            'content' => $content,
        ]);

        return $resource;
    }

    public static function updateResource($heading = null, $uploadId = null, $id = null, $category_id = null, $description = null, $content = null)
    {
        self::whereId($id)->update([
            'heading' => $heading,
            'slug' => Str::slug($heading),
            'upload_id' => $uploadId,
            'resource_category_id' => $category_id,
            'description' => $description,
            'content' => $content,
        ]);
    }

    public static function deleteResource($id = null)
    {
        return self::whereId($id)->delete();
    }

    public static function deleteResourceOfCategory($id = null)
    {
        return self::where('resource_category_id',$id)->delete();
    }

    public static function singleLibraryResource($resource_id){
        return self::whereId($resource_id)->with('libraryPermissions')->first()->toArray();
    }

    public static function resourcesForApi(){

        $user_plan = Helpers::getUser()->plan_name;

        $permission_id = $user_plan === 'Freemium' || $user_plan === 'Core' ? $user_plan === 'Core' ? 2 : 1 : 3;

        return self::whereHas('libraryPermissions', function ($q) use ($permission_id){

            $q->whereIn('permission', [4,$permission_id]);

        })->get();

    }

    public static function updateCategory($current,$new)
    {
        self::where('resource_category_id',$current)->update(['resource_category_id' => $new]);
    }


}
