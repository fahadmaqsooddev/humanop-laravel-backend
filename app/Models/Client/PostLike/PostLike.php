<?php

namespace App\Models\Client\PostLike;

use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // relations
    public function user(){

        return $this->belongsTo(User::class,'user_id','id');
    }


    // query
    public static function createPostLike($post_id = null){

        $data['user_id'] = Helpers::getWebUser()->id;

        $data['post_id'] = $post_id;

        $post_like = self::where($data)->first();

        if ($post_like){

            $post_like->delete();

        }else{

            self::create($data);
        }

    }

    public static function createCommentLike($comment_id = null){

        $data['user_id'] = Helpers::getWebUser()->id;

        $data['post_comment_id'] = $comment_id;

        $comment_like = self::where($data)->first();

        if ($comment_like){

            $comment_like->delete();

        }else{

            self::create($data);
        }

    }
}
