<?php

namespace App\Models\Client\Story;

use App\Helpers\Helpers;
use App\Models\Client\StoryView\StoryView;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Story extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['upload_url','is_viewed'];

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
    public function getUploadUrlAttribute(){

        if ($this->file_type === 'image'){

            return Helpers::getImage($this->upload_id);

        }elseif ($this->file_type === 'video'){

            return Helpers::getVideo($this->upload_id, 1);

        }else{

            return [];
        }


    }

    public function getIsViewedAttribute(){

        return $this->storyViews()->where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->exists() ? 1 : 0;
    }


    // accessor
    public function getCreatedAtAttribute($value){

        return Carbon::parse($value)->diffForHumans();
    }

    // query
    public static function addStory($data){

        $data['user_id'] = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

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

            ->get();
    }

    public static function deleteStory($id = null){

        self::whereId($id)->delete();
    }
}
