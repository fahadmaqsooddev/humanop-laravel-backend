<?php

namespace App\Models\Client\PostComment;

use App\Helpers\Helpers;
use App\Models\Client\PostLike\PostLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['is_liked_comment'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // relation
    public function user(){

        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function commentLikes(){

        return $this->hasMany(PostLike::class,'post_comment_id','id');
    }


    // appends
    public function getIsLikedCommentAttribute(){

        return $this->commentLikes()->where('user_id', Helpers::getWebUser()->id)->exists();
    }

    // query
    public static function createPostComment($data = null){

        self::create($data);
    }
}
