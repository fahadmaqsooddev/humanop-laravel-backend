<?php

namespace App\Models\B2B;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Upload\Upload;
use App\Helpers\Helpers;

class B2BSupport extends Model
{
    use HasFactory;
    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }
 
    protected $appends=['photo_url'];



    // scope

    public function scopeSelection($query)
    {
        return $query->select(['id', 'title',  'description', 'image_id']);
    }

    // appends
    public function getPhotoUrlAttribute()
    {

        return Helpers::getImage($this->image_id);
    }



    // relationis
    public function image()
    {
        return $this->belongsTo(Upload::class, 'image_id', 'id');
    }

    public static function createSupport($data=null, $imageid=null){
        $data['image_id']=$imageid;
        return self::create($data);
    }

    public static function AllSupport(){
        return self::with('image')->get();
    }

    public static function singleSupport($id){
        return self::with('image')->find($id);
    }
}
