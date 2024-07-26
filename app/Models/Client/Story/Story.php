<?php

namespace App\Models\Client\Story;

use App\Helpers\Helpers;
use App\Models\Client\StoryView\StoryView;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Story extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['photo_url','is_viewed'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // relations
    public function user(){

        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function storyViews(){
        return $this->hasMany(StoryView::class,'story_id','id');
    }

    // appends
    public function getPhotoUrlAttribute(){

        return Helpers::getImage($this->upload_id);
    }

    public function getIsViewedAttribute(){

        return $this->storyViews()->where('user_id', Helpers::getWebUser()->id)->exists() ? 1 : 0;
    }


    // accessor
    public function getCreatedAtAttribute($value){

        return Carbon::parse($value)->diffForHumans();
    }

    // query
    public static function addStory($data){

        $data['user_id'] = Helpers::getWebUser()->id;

        self::create($data);
    }

    public static function loggedInUserStory(){

        return self::where('user_id', Helpers::getWebUser()->id)

            ->where('created_at', ">", Carbon::now()->subDay())

            ->first();
    }

    public static function userStories($user_id = null){

        return self::has('user')->with('user')

            ->where('created_at', ">", Carbon::now()->subDay())

            ->where('user_id', $user_id)

            ->first();
    }
}
