<?php

namespace App\Models\Client\Post;

use App\Helpers\Helpers;
use App\Models\Client\PostComment\PostComment;
use App\Models\Client\PostLike\PostLike;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['photo_url','is_post_liked'];

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

    public function postLikes(){

        return $this->hasMany(PostLike::class,'post_id','id');
    }

    public function postComments(){

        return $this->hasMany(PostComment::class,'post_id','id');
    }

    public function sharedPost(){

        return $this->belongsTo(Post::class,'post_id', 'id');
    }

    public function postShares(){

        return $this->hasMany(Post::class,'post_id','id');
    }


    // appends
    public function getPhotoUrlAttribute(){

        return Helpers::getImage($this->upload_id);
    }

    public function getIsPostLikedAttribute(){

        return $this->postLikes()->where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->exists();
    }

    // accessor
    public function getCreatedAtAttribute($value){

        return Carbon::parse($value)->diffForHumans();
    }


    // query
    public static function createPost($data = null){

        return self::create($data);
    }

    public static function allPosts(){

        return self::with(['user' => function($q){

            $q->select(['id','first_name','last_name']);

        }, 'postComments' => function($q){

            $q->with('user:id,first_name,last_name')->latest()

                ->withCount('commentLikes');

        }, 'sharedPost' => function($q){

            $q->with('user:id,first_name,last_name');

        }, 'postShares' => function($q){

            $q->with('user:id');

        }])

            ->withCount(['postLikes', 'postComments', 'postShares'])

            ->latest()

            ->get();
    }

    public static function deletePost($post_id = null){

        self::whereId($post_id)->where('user_id', Helpers::getWebUser()->id)->delete();
    }

    public static function post($post_id = null){

        return self::with('user:id,first_name,last_name')->whereId($post_id)->first();
    }

    public static function updatePost($data = null, $post_id = null){

        $post = self::whereId($post_id)->first();

        $post->update($data);

        return $post->refresh();
    }

    public static function allPostsForApi($request = null){

        $order_by = isset($request->order_by) ? $request->order_by : "id";

        $order = isset($request->order) ? $request->order : "DESC";

        $all_posts = self::with(['user' => function($q){

            $q->select(['id','first_name','last_name']);

        }, 'sharedPost' => function($q){

            $q->with('user:id,first_name,last_name');

        }, 'postShares' => function($q){

            $q->with('user:id');

        }])

            ->withCount(['postLikes', 'postComments', 'postShares'])

            ->orderBy($order_by, $order);

        return Helpers::pagination($all_posts, $request->input('pagination'), $request->input('per_page'));
    }
}
