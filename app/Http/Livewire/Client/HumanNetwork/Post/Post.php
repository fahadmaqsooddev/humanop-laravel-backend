<?php

namespace App\Http\Livewire\Client\HumanNetwork\Post;

use App\Helpers\Helpers;
use App\Models\Client\PostComment\PostComment;
use App\Models\Client\PostLike\PostLike;
use App\Models\Upload\Upload;
use Livewire\Component;
use Livewire\WithFileUploads;

class Post extends Component
{
    use WithFileUploads;

    protected $listeners = ['createPostModal' => 'toggleCreatePostModal', 'postShareModal' => 'toggleSharePostModel'];

    protected $rules = [
        'description' => 'required|max:1000',
        'post_image' => 'nullable|image||mimes:jpg,png,jpeg|max:3072'
    ];

    public $description, $post_image, $posts = [], $post_comment, $logged_in_user,
        $post_id, $shared_post_description, $single_post, $is_shared_post;

    public function toggleCreatePostModal(){

        $this->emit('toggleCreatePostFormModal');
    }

    public function editPost($post_id){

        $post = \App\Models\Client\Post\Post::post($post_id);

        $this->emit('toggleEditPostFormModal');

        $this->description = $post->description;

        $this->post_id = $post->id;

        $this->is_shared_post = (!empty($post->post_id) ? true : false);

    }

    public function addPost(){

        $this->validate();

        $data = [];

        if ($this->post_image){

            $upload_id = Upload::uploadFile($this->post_image, 200, 200, 'base64Image','png', true);

            $data['upload_id'] = $upload_id;
        }

        $data['description'] = $this->description;

        \App\Models\Client\Post\Post::createPost($data);

        $this->emit('toggleCreatePostFormModal');

        $this->reset();

        session()->flash('success', "Post created successfully");

        $this->emit('hideSuccessAlert');
    }

    public function postLike($post_id){

        PostLike::createPostLike($post_id);

    }

    public function createPostComment($post_id){

//        $this->validate(['post_comment' => 'required']);

        $data['comment'] = $this->post_comment;

        $data['user_id'] = Helpers::getWebUser()->id;

        $data['post_id'] = $post_id;

        PostComment::createPostComment($data);

        $this->reset();

        session()->flash('success', "Comment posted successfully");

        $this->emit('hideSuccessAlert');
    }

    public function commentLikes($comment_id = null){

        PostLike::createCommentLike($comment_id);
    }

    public function deletePost($post_id){

        \App\Models\Client\Post\Post::deletePost($post_id);
    }

    public function updatePost(){

        $this->validate();

        $data = [];

        if ($this->post_image){

            $upload_id = Upload::uploadFile($this->post_image, 200, 200, 'base64Image','png', true);

            $data['upload_id'] = $upload_id;
        }

        $data['description'] = $this->description;

        \App\Models\Client\Post\Post::updatePost($data, $this->post_id);

        $this->emit('toggleEditPostFormModal');

        $this->reset();

        session()->flash('success', "Post updated successfully");

        $this->emit('hideSuccessAlert');

    }

    public function deleteComment($comment_id){

        PostComment::whereId($comment_id)->delete();
    }

    public function toggleSharePostModel(){

        $this->emit('toggleSharePostFormModal');
    }

    public function sharePost($post_id){

        $this->single_post = \App\Models\Client\Post\Post::post($post_id);

        $this->emit('toggleSharePostFormModal');

        $this->post_id = $this->single_post->id ?? null;

    }

    public function saveSharedPost(){

        $this->validate(['shared_post_description' => 'required']);

        $data['description'] = $this->shared_post_description;

        $data['post_id'] = $this->post_id;

        \App\Models\Client\Post\Post::createPost($data);

        $this->emit('toggleSharePostFormModal');

        $this->reset();

        session()->flash('success', "Post shared successfully");

        $this->emit('hideSuccessAlert');
    }

    public function render()
    {

        $this->posts = \App\Models\Client\Post\Post::allPosts();

        $this->logged_in_user = Helpers::getWebUser();

        return view('livewire.client.human-network.post.post');
    }
}
