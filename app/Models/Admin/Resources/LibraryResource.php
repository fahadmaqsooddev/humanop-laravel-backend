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

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute(){
        return Helpers::getImage($this->upload_id,'profile_pic.png');
    }

    public static function getResources()
    {
        return self::get();
    }

    public static function createResource($heading = null, $uploadId = null)
    {
        $resource =  self::create([
            'heading' => $heading,
            'slug' => Str::slug($heading),
            'upload_id' => $uploadId,
        ]);

        return $resource;
    }

    public static function deleteResource($id = null)
    {
        return self::whereId($id)->delete();
    }
}
