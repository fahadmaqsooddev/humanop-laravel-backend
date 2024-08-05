<?php

namespace App\Models\Client\StoryView;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryView extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }


    // query
    public static function addStoryView($story_id = null){

        $data['story_id'] = $story_id;

        $data['user_id'] = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

        $story_view_exists = self::where('story_id', $story_id)->where('user_id', $data['user_id'])->exists();

        if (!$story_view_exists){

            self::create($data);
        }

    }

    public static function storyViews($story_id = null){

        return self::has('user')->with(['user' => function($q){

            $q->select(['id','first_name','last_name']);

        }])

            ->where('story_id', $story_id)

            ->get();

    }
}
