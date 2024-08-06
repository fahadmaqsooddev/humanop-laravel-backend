<?php

namespace App\Http\Controllers\Api\ClientController\HumanNetwork;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Post\CreateCommentRequest;
use App\Http\Requests\Api\Client\Post\CreatePostRequest;
use App\Http\Requests\Api\Client\Post\DeleteCommentRequest;
use App\Http\Requests\Api\Client\Post\DeletePostRequest;
use App\Http\Requests\Api\Client\Post\EditCommentRequest;
use App\Http\Requests\Api\Client\Post\EditPostRequest;
use App\Http\Requests\Api\Client\Post\LikeUnLikeCommentRequest;
use App\Http\Requests\Api\Client\Post\LikeUnLikePostRequest;
use App\Http\Requests\Api\Client\Post\SharePostRequest;
use App\Http\Requests\Api\Client\Post\SingleCommentRequest;
use App\Http\Requests\Api\Client\Post\SinglePostRequest;
use App\Models\Client\Post\Post;
use App\Models\Client\PostComment\PostComment;
use App\Models\Client\PostLike\PostLike;
use App\Models\Upload\Upload;
use Illuminate\Http\Request;

class PostController extends Controller
{

    protected $post;

    public function __construct(Post $post)
    {
        $this->middleware('auth:api');

        $this->post = $post;
    }

    public function posts(Request $request){

        try {

            $posts = Post::allPostsForApi($request);

            return Helpers::successResponse('All posts', $posts, $request->input('pagination'));

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function createPost(CreatePostRequest $request){

        try {

            $dataArray = $request->only($this->post->getFillable());

            $dataArray['user_id'] = Helpers::getUser()->id;

            $post = Post::createPost($dataArray);

            if ($request->hasFile('post_image') && !empty($request->file('post_image'))){

                $upload_id = Upload::uploadFile($request->file('post_image'), 200, 200, 'base64Image', 'png', true);

                $post->upload_id = $upload_id;

                $post->save();
            }

            return Helpers::successResponse('Post created successfully');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function editPost(EditPostRequest $request){

        try {

            $dataArray = $request->only($this->post->getFillable());

            $dataArray['user_id'] = Helpers::getUser()->id;

            $post = Post::updatePost($dataArray, $request->input('id'));

            if ($request->hasFile('post_image') && !empty($request->file('post_image'))){

                $upload_id = Upload::uploadFile($request->file('post_image'), 200, 200, 'base64Image', 'png', true);

                $post->upload_id = $upload_id;

                $post->save();
            }

            return Helpers::successResponse('Post created successfully');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function post(SinglePostRequest $request){

        try {

            $post = Post::post($request->input('id'));

            return Helpers::successResponse('Post', $post);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function deletePost(DeletePostRequest $request){

        try {

            Post::deletePost($request->input('id'));

            return Helpers::successResponse('Post deleted');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }


    public function likeUnLikePost(LikeUnLikePostRequest $request){

        try {

            PostLike::createPostLikeForApi($request);

            return Helpers::successResponse('Post ' . $request->type . 'd successfully');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }


    public function sharePost(SharePostRequest $request){

        try {

            $dataArray = $request->only($this->post->getFillable());

            $dataArray['user_id'] = Helpers::getUser()->id;

            Post::createPost($dataArray);

            return Helpers::successResponse('Post shared successfully');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function comments(SinglePostRequest $request){

        try {

            $comments = PostComment::getPostComments($request->input('id'));

            return Helpers::successResponse('Post comments', $comments);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function comment(SingleCommentRequest $request){

        try {

            $comment = PostComment::singleComment($request->input('comment_id'));

            return Helpers::successResponse('Post comments', $comment);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function createComment(CreateCommentRequest $request){

        try {

            $comment = new PostComment();

            $dataArray = $request->only($comment->getFillable());

            $dataArray['user_id'] = Helpers::getUser()->id;

            PostComment::createPostComment($dataArray);

            return Helpers::successResponse('Comment created');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function editComment(EditCommentRequest $request){

        try {

            $comment = new PostComment();

            $dataArray = $request->only($comment->getFillable());

            $dataArray['user_id'] = Helpers::getUser()->id;

            PostComment::createPostComment($dataArray, $request->input('comment_id'));

            return Helpers::successResponse('Comment is updated');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function deleteComment(DeleteCommentRequest $request){

        try {

            PostComment::deleteComment($request->input('comment_id'));

            return Helpers::successResponse('Comment is deleted');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function likeUnLikeComment(LikeUnLikeCommentRequest $request){

        try {

            PostLike::createPostLikeForApi($request);

            return Helpers::successResponse('Comment ' . $request->type . 'd successfully');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


}
