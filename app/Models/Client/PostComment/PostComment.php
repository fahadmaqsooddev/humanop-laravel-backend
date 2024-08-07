<?php

namespace App\Models\Client\PostComment;

use App\Helpers\Helpers;
use App\Http\Livewire\Client\HumanNetwork\Post\Post;
use App\Models\Client\PostLike\PostLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

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

    public function post(){

        return $this->belongsTo(Post::class,'post_id','id');
    }


    // appends
    public function getIsLikedCommentAttribute(){

        return $this->commentLikes()->where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->exists();
    }

    // query
    public static function createPostComment($data = null, $comment_id = null){

        $comment = self::whereid($comment_id)->first();

        if ($comment){

            $comment->update(['comment' => $data['comment']]);

        }else{

            self::create($data);
        }

    }

    public static function getPostComments($post_id = null){

        $comments = self::has('user')->where('post_id', $post_id)

            ->with('user:id,first_name,last_name')

            ->withCount('commentLikes')

            ->get();

        Helpers::getWebUser() ? Session::put(['post_comments' => $comments]) : "";

        return $comments;
    }

    public static function singleComment($comment_id = null){

        return self::with('user:id,first_name,last_name')->whereId($comment_id)->first();
    }

    public static function deleteComment($id = null){

        self::whereId($id)->delete();
    }
}
