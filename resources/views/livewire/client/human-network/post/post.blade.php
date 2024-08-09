<div>

    <style>
        .form-control{
            color: black;
        }

        .text-orange{
            color: #f2661c;
        }
    </style>

    <div class="row mt-2">

        <div class="text-center mx-auto w-50">
            @if(session()->has('success'))
                <p class="alert alert-success">{{session('success')}}</p>
            @endif
        </div>

        <div class="col-12 col-lg-8">
            <button class="btn bg-gradient-primary" wire:click="$emit('createPostModal')"> <i class="fas fa-plus pe-2"></i> Create Post</button>

{{--            hidden--}}
            <button id="create_post" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#create-post-modal" hidden>
                Create Post
            </button>

            @foreach($posts as $post)

            <div class="card">
                <div class="card-header d-flex align-items-center border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <a href="javascript:void(0);">
                            <img src="{{ $post['user']['user_picture_url'] ?? URL::asset('assets/img/team-4.jpg') }}" class="avatar" alt="profile-image">
                        </a>
                        <div class="mx-3">
                            <a href="javascript:;" class="text-dark font-weight-600 text-sm">{{$post['user'] ? $post['user']['first_name'] . ' ' . $post['user']['last_name'] : "John Snow" }}</a>
                            <small class="d-block text-muted">{{$post['created_at']}}</small>
                        </div>
                    </div>
                    <div class="text-end ms-auto">
                        @if($logged_in_user->id === $post->user_id)

                            <button wire:click="editPost({{$post->id}})" type="button" class="btn bg-gradient-primary mb-0 text-white">
                                <i class="fa fa-pencil"></i>

{{--                                hidden--}}
                                <button id="edit_post" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#edit-post-modal" hidden>
                                    Edit Post
                                </button>
                            </button>
                            <button wire:click="deletePost({{$post->id}})" wire:confirm="Are you sure you want to delete this post?" type="button" class="btn btn-danger mb-0">
                                <i class="fa fa-trash"></i>
                            </button>

                        @else

                            <button wire:click="followUser({{$post->user_id}})" type="button" class="btn btn-sm  {{($post->user->is_follow ?? null) ? "bg-secondary" : "bg-gradient-primary"}} mb-0" style="color: white;">
{{--                                <i class='fas fa-plus pe-2'></i>--}}
                                {{$post->user ? $post->user->is_follow ? "Following" : "Follow" : "Follow"}}
                            </button>

                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <p class="mb-4 text-white">
                        {{$post['description']}}
                    </p>
                    @if($post['photo_url'])
                        <div class="mx-auto">
                            <img alt="Image placeholder" src="{{$post['photo_url']['url']}}" class="img-fluid border-radius-lg shadow-lg w-100">
                        </div>
                    @endif

{{--                    If Post is shared--}}

                    @if($post['sharedPost'])

                    <div class="card">
                        <div class="card-header d-flex align-items-center border-bottom py-3">
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0);">
                                    <img src="{{ $post['sharedPost']['user']['user_picture_url'] ?? URL::asset('assets/img/team-4.jpg') }}" class="avatar" alt="profile-image">
                                </a>
                                <div class="mx-3">
                                    <a href="javascript:;" class="text-dark font-weight-600 text-sm">{{$post['sharedPost']['user'] ? $post['sharedPost']['user']['first_name'] . ' ' . $post['sharedPost']['user']['last_name'] : "John Snow" }}</a>
                                    <small class="d-block text-muted">{{$post['sharedPost']['created_at']}}</small>
                                </div>
                            </div>
                            <div class="text-end ms-auto">
                                @if($logged_in_user->id === $post['sharedPost']['user_id'])

                                @else

                                    <button wire:click="followUser({{$post['sharedPost']->user_id}})" type="button" class="btn btn-sm  {{($post['sharedPost']->user->is_follow ?? null) ? "bg-secondary" : "bg-gradient-primary"}} mb-0" style="color: white">
{{--                                        <i class='fas fa-plus pe-2'></i>--}}
                                        {{$post['sharedPost']->user ? $post['sharedPost']->user->is_follow ? "Following" : "Follow" : "Follow"}}
                                    </button>

                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-4 text-white">
                                {{$post['sharedPost']['description']}}
                            </p>
                            @if($post['sharedPost']['photo_url'] ?? null)
                                <div class="mx-auto">
                                    <img alt="Image placeholder" src="{{$post['sharedPost']['photo_url']['url'] ?? null}}" class="img-fluid border-radius-lg shadow-lg w-100">
                                </div>
                            @endif
                        </div>
                    </div>

                    @endif



                    <div class="row align-items-center px-2 mt-4 mb-2">
                        <div class="col-sm-6">
                            <div class="d-flex text-white">
                                <a wire:click='postLike({{$post->id}})' class="d-flex align-items-center text-white">
                                    <i class="ni ni-like-2 me-1 cursor-pointer {{ $post['is_post_liked'] ? "text-orange" : "text-white" }}"></i>
                                    <span class="text-sm me-3">{{$post['post_likes_count']}}</span>
                                </a>
                                <div class="d-flex align-items-center">
                                    <i wire:click="showComments({{$post->id}})" class="ni ni-chat-round me-1 cursor-pointer"></i>
                                    <span class="text-sm me-3">{{$post['post_comments_count']}}</span>

{{--                                    Hidden--}}
                                    <button id="post_comments" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#post-comments-modal" hidden>
                                        Post Comments
                                    </button>
                                </div>
                                <a wire:click="sharePost({{$post->id}})" class="d-flex align-items-center text-white">
                                    <i class="ni ni-curved-next me-1 cursor-pointer"></i>
                                    <span class="text-sm me-2">{{$post['post_shares_count']}}</span>
                                </a>

{{--                                Hidden--}}
                                <button id="share_post" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#share-post-modal" hidden>
                                    Share Post
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-6 d-none d-sm-block">
                            <div class="d-flex align-items-center justify-content-sm-end">
                                <div class="d-flex align-items-center">
                                    @foreach($post['postShares'] as $post_share)
                                        <a href="javascript:void(0)" class="avatar avatar-xs rounded-circle text-primary">
    {{--                                        <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-5.jpg') }}" class="text-">--}}
                                            <img alt="Image placeholder" src="{{ $post_share['user']['photo_url']['thumbnail_url'] ?? URL::asset('assets/img/team-3.jpg') }}" class="rounded-circle">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                    </div>
                    <!-- Comments -->
                    <div class="mb-1">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <img alt="Image placeholder" class="avatar rounded-circle me-3" src="{{ \App\Helpers\Helpers::getWebUser()['user_picture_url'] ?? URL::asset('assets/img/team-4.jpg') }}">
                            </div>
                            <div class="flex-grow-1 my-auto">
                                <div class="">
                                    <div wire:click="showComments({{$post->id}})" class="form-control text-secondary cursor-pointer">Comment as {{$logged_in_user->first_name}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="pt-3 d-flex justify-content-center">
                @if($posts->hasMorePages())
                    <button class="btn" wire:click.prevent="loadMore" style="background-color: #f2661c; color: white; width: 60%; margin: auto;">
                        Load more
                    </button>
                @endif
            </div>
        </div>


        <div class="col-12 col-lg-4">
                <div class="card mb-3 mt-lg-0 mt-4">
                    <div class="card-body pb-0 text-white">
                        <div class="row align-items-center mb-3">
                            <div class="col-9">
                                <h5 class="mb-1 text-gradient text-primary">
                                    <a href="javascript:;">Digital Marketing</a>
                                </h5>
                            </div>
                            <div class="col-3 text-end">
                                <div class="dropstart">
                                    <a href="javascript:;" class="text-secondary" id="dropdownMarketingCard" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-lg-start px-2 py-3" aria-labelledby="dropdownMarketingCard">
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Edit Team</a></li>
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Add Member</a></li>
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">See Details</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item border-radius-md text-danger" href="javascript:;">Remove Team</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <p>A group of people who collectively are responsible for all of the work necessary to produce working, validated assets.</p>
                        <ul class="list-unstyled mx-auto">
                            <li class="d-flex text-white">
                                <p class="mb-0 text-white">Industry:</p>
                                <span class="badge badge-secondary ms-auto">Marketing Team</span>
                            </li>
                            <li>
                                <hr class="horizontal dark">
                            </li>
                            <li class="d-flex text-white">
                                <p class="mb-0 text-white">Rating:</p>
                                <div class="rating ms-auto">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                            </li>
                            <li>
                                <hr class="horizontal dark">
                            </li>
                            <li class="d-flex text-white">
                                <p class="mb-0 text-white">Members:</p>
                                <div class="avatar-group ms-auto">
                                    <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Alexa Tompson">
                                        <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-1.jpg') }}">
                                    </a>
                                    <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Romina Hadid">
                                        <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-2.jpg') }}">
                                    </a>
                                    <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Alexander Smith">
                                        <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-3.jpg') }}">
                                    </a>
                                    <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Martin Doe">
                                        <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-4.jpg') }}">
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card mt-4 mb-3">
                    <div class="card-body pb-0 text-white">
                        <div class="row align-items-center mb-3">
                            <div class="col-9">
                                <h5 class="mb-1 text-gradient text-primary">
                                    <a href="javascript:;">Design</a>
                                </h5>
                            </div>
                            <div class="col-3 text-end">
                                <div class="dropstart">
                                    <a href="javascript:;" class="text-secondary" id="dropdownDesignCard" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-lg-start px-2 py-3" aria-labelledby="dropdownDesignCard">
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Edit Team</a></li>
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">Add Member</a></li>
                                        <li><a class="dropdown-item border-radius-md" href="javascript:;">See Details</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item border-radius-md text-danger" href="javascript:;">Remove Team</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <p>Because it's about motivating the doers. Because I’m here to follow my dreams and inspire other people to follow their dreams, too.</p>
                        <ul class="list-unstyled mx-auto">
                            <li class="d-flex text-white">
                                <p class="mb-0 text-white">Industry:</p>
                                <span class="badge badge-secondary ms-auto">Design Team</span>
                            </li>
                            <li>
                                <hr class="horizontal dark">
                            </li>
                            <li class="d-flex text-white">
                                <p class="mb-0 text-white">Rating:</p>
                                <div class="rating ms-auto">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </li>
                            <li>
                                <hr class="horizontal dark">
                            </li>
                            <li class="d-flex text-white">
                                <p class="mb-0 text-white">Members:</p>
                                <div class="avatar-group ms-auto">
                                    <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Martin Doe">
                                        <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-4.jpg') }}">
                                    </a>
                                    <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Romina Hadid">
                                        <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-3.jpg') }}">
                                    </a>
                                    <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Alexa Tompson">
                                        <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-1.jpg') }}">
                                    </a>
                                    <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Alexandra Smith">
                                        <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-5.jpg') }}">
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body p-3">
                        <div class="d-flex text-white">
                            <div class="avatar avatar-lg">
                                <img alt="Image placeholder" src="{{ URL::asset('assets/img/small-logos/logo-slack.svg') }}">
                            </div>
                            <div class="ms-2 my-auto">
                                <h6 class="mb-0 text-white">Slack Meet</h6>
                                <p class="text-xs mb-0">11:00 AM</p>
                            </div>
                        </div>
                        <p class="mt-3 text-white"> You have an upcoming meet for Marketing Planning</p>
                        <p class="mb-0 text-white"><b>Meeting ID:</b> 902-128-281</p>
                        <hr class="horizontal dark">
                        <div class="d-flex text-white">
                            <button type="button" class="btn btn-sm bg-gradient-success mb-0">
                                Join
                            </button>
                            <div class="avatar-group ms-auto">
                                <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Alexa Tompson">
                                    <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-1.jpg') }}">
                                </a>
                                <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Romina Hadid">
                                    <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-2.jpg') }}">
                                </a>
                                <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Alexander Smith">
                                    <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-3.jpg') }}">
                                </a>
                                <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Martin Doe">
                                    <img alt="Image placeholder" src="{{ URL::asset('assets/img/ivana-squares.jpg') }}">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body p-3">
                        <div class="d-flex text-white">
                            <div class="avatar avatar-lg">
                                <img alt="Image placeholder" src="{{ URL::asset('assets/img/small-logos/logo-invision.svg') }}">
                            </div>
                            <div class="ms-2 my-auto">
                                <h6 class="mb-0 text-white">Invision</h6>
                                <p class="text-xs mb-0">4:50 PM</p>
                            </div>
                        </div>
                        <p class="mt-3 text-white"> You have an upcoming video call for <span class="text-primary">Soft Design</span> at 5:00 PM.</p>
                        <p class="mb-0 text-white"><b>Meeting ID:</b> 111-968-981</p>
                        <hr class="horizontal dark">
                        <div class="d-flex text-white">
                            <button type="button" class="btn btn-sm bg-gradient-success mb-0">
                                Join
                            </button>
                            <div class="avatar-group ms-auto">
                                <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Alexa Tompson">
                                    <img alt="Image placeholder" src="{{ URL::asset('assets/img/teams-image.png') }}">
                                </a>
                                <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Romina Hadid">
                                    <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-2.jpg') }}">
                                </a>
                                <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Alexander Smith">
                                    <img alt="Image placeholder" src="{{ URL::asset('assets/img/team-3.jpg') }}">
                                </a>
                                <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Martin Doe">
                                    <img alt="Image placeholder" src="{{ URL::asset('assets/img/ivana-squares.jpg') }}">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
    </div>

    </div>


{{--    MODAL --}}

    <div>
        <div class="modal fade" id="create-post-modal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Post</h5>
                        <a type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                           aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </a>
{{--                        <button type="button" wire:click="$emit('createPostModal')" class="close btn btn-close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>--}}
                    </div>
                    <form wire:submit.prevent="addPost">
                        <div class="modal-body">

                            <label>Description</label>
                            <textarea type="text" wire:model="description" class="form-control" ></textarea>

                            @error('description')
                                <p class="text-danger">{{$message}}</p>
                            @enderror

                            <label>Image</label>
                            <input type="file" wire:model="post_image" class="form-control">
                            <span wire:loading.flex wire:target="post_image">
                                Image Uploading ...
                            </span>

                            @error('post_image')
                                <p class="text-danger">{{$message}}</p>
                            @enderror

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn bg-gradient-primary">post
                                <span wire:loading wire:target="addPost">
                                </span>
                            </button>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="modal fade" id="edit-post-modal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Post</h5>
                        <a type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                           aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </a>
{{--                        <button type="button" wire:click="$emit('postEditModal')" class="close btn btn-close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>--}}
                    </div>
                    <form wire:submit.prevent="updatePost">
                        <div class="modal-body">

                            <label>Description</label>
                            <textarea type="text" wire:model="description" class="form-control" ></textarea>


                            @error('description')
                            <p class="text-danger">{{$message}}</p>
                            @enderror


                            @if(!$is_shared_post)
                                <label>Image</label>
                                <input type="file" wire:model="post_image" class="form-control">
                                <span wire:loading.flex wire:target="post_image">
                                Image Uploading ...
                            </span>
                            @endif

                            @error('post_image')
                            <p class="text-danger">{{$message}}</p>
                            @enderror


                            <input type="text" wire:model="post_id" hidden>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn bg-gradient-primary">post
                                <span wire:loading wire:target="updatePost">
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    <div>
        <div class="modal fade" id="share-post-modal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Share Post</h5>
                        <a type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                           aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </a>
{{--                        <button type="button" wire:click="$emit('postShareModal')" class="close btn btn-close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>--}}
                    </div>
                    <form wire:submit.prevent="saveSharedPost">
                        <div class="modal-body">

                            <p>{{$single_post->description ?? ""}}</p>

                            @if($single_post['photo_url'] ?? null)
                                <img src="{{$single_post['photo_url']['url'] ?? ""}}" width="25%">
                            @endif

                            <br>

                            <label>Description</label>
                            <textarea type="text" wire:model="shared_post_description" class="form-control" ></textarea>

                            @error('shared_post_description')
                                <p class="text-danger">{{$message}}</p>
                            @enderror

                            <input type="text" wire:model="post_id" hidden>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn bg-gradient-primary">share
                                <span wire:loading wire:target="saveSharedPost">
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    <div>
        <div class="modal fade" id="post-comments-modal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Post Comment's</h5>
                        <a type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                           aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </a>
{{--                        <button type="button" wire:click="$emit('postCommentsModal')" class="close btn btn-close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>--}}
                    </div>

                    <div class="modal-body">

                        <div class="p-2" style="padding-left: 15px;">
                            @if(empty($post_comments[0]))
                                <p class="text-center">No comments ...</p>
                            @endif

                            @foreach($post_comments as $comment)

                                <div class="d-flex p-1">
                                    <div class="flex-shrink-0">
                                        <img alt="Image placeholder" class="avatar rounded-circle" src="{{ $comment['user'] ? $comment['user']['user_picture_url'] : URL::asset('assets/img/bruce-mars.jpg') }}">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="text-black-50" style="padding: 5px; border-radius: 9px; width: max-content; background-color: #ebe6e6;max-width: 423px; word-wrap: break-word;">
                                    <span class="h5 mt-0" style="font-size: 12px; font-weight: 600;">
                                        <b>
                                            {{$comment['user'] ? $comment['user']['first_name'] . ' ' . $comment['user']['last_name'] : ""}}
                                        </b>

                                        <span>
                                            @if($comment->user_id === $logged_in_user->id)
                                                &nbsp;<a wire:confirm="Are you sure you want to delete comment ?" wire:click="deleteComment({{$comment->id}})" class="text-danger small">
                                                        <i class="fa fa-trash me-1 cursor-pointer"></i>
                                                    </a>

                                                &nbsp;<a wire:click="editComment({{$comment->id}})" class="text-orange small">
                                                        <i class="fa fa-pencil me-1 cursor-pointer"></i>
                                                    </a>
                                            @endif
                                            </span>
                                    </span>
                                            <br>

                                            <span class="text-sm">{{$comment['comment']}}</span>
                                        </div>
                                        <div class="d-flex">
                                            <a wire:click='commentLikes({{$comment->id}})'>
                                                <i class="ni ni-like-2 me-1 cursor-pointer {{$comment['is_liked_comment'] ? "text-orange" : "text-secondary"}}"></i>
                                            </a>
                                            <span class="text-sm me-2">{{$comment['comment_likes_count']}} likes</span>
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                            <div class="d-flex mt-4">
                                <div class="flex-shrink-0">
                                    <img alt="Image placeholder" class="avatar rounded-circle me-3" src="{{ \App\Helpers\Helpers::getWebUser()['user_picture_url'] ?? URL::asset('assets/img/team-4.jpg') }}">
                                </div>
                                <div class="flex-grow-1 my-auto">
                                    <form>
                                        <div class="input-group">
                                            <textarea wire:model="post_comment" class="form-control border-end" aria-label="With textarea" rows="1" placeholder="Comment as {{$logged_in_user->first_name}}"></textarea>
                                            <div class="input-group-prepend">
                                                <a  type="button" wire:click="createPostComment({{$post_id}})" class="input-group text-orange p-3" style="font-size: 30px; cursor:pointer">
                                                    <i class="ni ni-send"></i>
                                                </a>
                                            </div>
                                        </div>

                                        {{--                                        <input wire:model="post_id" hidden>--}}

                                        @error('post_comment')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </form>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Because she competes with no one, no one can compete with her. --}}
</div>

@push('javascript')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    window.livewire.on('toggleCreatePostFormModal', () => {

        $('#create_post').click()

        // $('#create-post-modal').modal('toggle')
    })
    window.livewire.on('toggleEditPostFormModal', () => $('#edit_post').click())
    window.livewire.on('toggleSharePostFormModal', () => $('#share_post').click())
    window.livewire.on('togglePostCommentsModal', () => $('#post_comments').click())
    window.livewire.on('hideSuccessAlert', () => { setTimeout(function (){
        $('.alert-success').fadeOut('fast');
    }, 3000)})

</script>
@endpush
