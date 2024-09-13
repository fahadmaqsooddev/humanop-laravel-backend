<div class="">

    <div class="container-fluid px-0">
        <div class="text-center  ">
            @if(session('success'))
                <p class="alert alert-success">{{session('success')}}</p>
            @endif
        </div>

        <div class="page-header min-height-300 border-radius-xl mt-4">
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6 ">
            <div class="row">
                <!-- <div class="col-sm-12">
                    <div class="avatar avatar-xl ">
                        <img src="{{ URL::asset('assets/img/bruce-mars.jpg') }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>

                <div class="col-sm-12 ">
                    <div class="">
                        <h5 class="mb-1 text-white">
                            {{$user->first_name . ' ' . $user->last_name}}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm text-white">
                            CEO / Co-Founder
                        </p>
                    </div>
                </div>

                <div class="col-sm-12">

                    <div class="nav-wrapper ">
                        <ul class="nav nav-pills nav-fill p-1 bg-transparent">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1">
                                    <button class="ms-1 bg-gradient-primary rounded-3 p-2">
                                        <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(603.000000, 0.000000)">
                                                            <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                                                            </path>
                                                            <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                                                            <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                        Overview</button>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1">
                                    <button class="ms-1 bg-gradient-primary rounded-3 p-2">
                                        <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>document</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(154.000000, 300.000000)">
                                                            <path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" opacity="0.603585379"></path>
                                                            <path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                        Teams</button>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1">
                                    <button class="ms-1 bg-gradient-primary rounded-3 p-2">
                                        <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>settings</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(304.000000, 151.000000)">
                                                            <polygon class="color-background" opacity="0.596981957" points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667">
                                                            </polygon>
                                                            <path class="color-background" d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z" opacity="0.596981957"></path>
                                                            <path class="color-background" d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                        Projects</button>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div> -->

            <div class="page-header min-height-100 border-radius-xl">
            </div>
            <div class="  mt-n6 ">
                <div class="d-flex justify-content-between flex-wrap">

                    <div class="d-flex">
                        <div class="col-auto pb-sm-4">
                            <div class="avatar avatar-xl avatar-icon  ">
                                <img src="{{ $user['photo_url']['thumbnail_url'] ?? null }}" alt="profile_image"
                                     class="w-100 border-radius-lg shadow-sm  ">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="">
                                <h5 class="mb-1 text-white">
                                    {{$user['first_name']}}  {{$user['last_name']}}
                                </h5>

                                 <p class="mb-0 font-weight-bold text-sm text-white">
                            CEO / Co-Founder
                        </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card remove-scrollbar">
                    <div class="card-body d-flex">
                        <div class="col-lg-1 col-md-2 col-sm-3 col-4 text-center">
                            <button wire:click="$emit('addStoryModal')" class="avatar avatar-lg border-1 rounded-circle bg-gradient-primary">
                                <i class="fas fa-plus text-white"></i>
                            </button>

        {{--                            Hidden--}}
                            <button id="create_story" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#create-story-form-modal" hidden>
                                Share Post
                            </button>
                            <p class="mb-0 text-sm text-white" style="margin-top:6px;">Add story</p>
                        </div>
                        @foreach($story_users as $story_user)

                            @if($user->id === $story_user->id)
                                <div class="col-lg-1 col-md-2 col-sm-3 col-4 text-center">
                                    {{--                                <a style="cursor: pointer;" wire:click="viewStoryModal({{$story_user->id}})" class="avatar avatar-lg rounded-circle border-1 border-primary {{$story_user['stories'][0]['is_viewed'] ? "" : "bg-gradient-primary"}}">--}}
                                    <a href="{{url('/client/stories') . '?id=' . $story_user->id}}" style="cursor: pointer;" class="avatar avatar-lg rounded-circle border-1 border-primary {{$story_user['stories'][0]['is_viewed'] ? "" : "bg-gradient-primary"}}">
                                        <img alt="Image placeholder" class="p-1" src="{{ $story_user['photo_url']['url'] ?? null }}">
                                    </a>
                                    <p class="mb-0 text-sm text-white">Your Story</p>
                                </div>
                            @else
                                <div class="col-lg-1 col-md-2 col-sm-3 col-4 text-center">
                                    {{--                                <a style="cursor: pointer;" wire:click="viewStoryModal({{$story_user->id}})" class="avatar avatar-lg rounded-circle border-1 border-primary {{$story_user['stories'][0]['is_viewed'] ? "" : "bg-gradient-primary"}}">--}}
                                    <a href="{{url('/client/stories') . '?id=' . $story_user->id}}" style="cursor: pointer;" class="avatar avatar-lg rounded-circle border-1 border-primary {{$story_user['stories'][0]['is_viewed'] ? "" : "bg-gradient-primary"}}">
                                        <img alt="Image placeholder" class="p-1" src="{{ $story_user['photo_url']['url'] ?? null }}">
                                    </a>
                                    <p class="mb-0 text-sm text-white">{{$story_user->first_name . ' ' . $story_user->last_name}}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{--  MODAL      Forms--}}

    <div>
        <div class="modal fade" id="create-story-form-modal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Story</h5>
                        <a type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>

                    <form wire:submit.prevent="uploadStory">
                        <div class="modal-body">
                            <input type="file" accept="video/mp4, image/png, image/jpg, image/jpeg, image/svg, image/gif"
                                   wire:model="story_photo" class="form-control">
                            <span wire:loading.flex wire:target="story_photo">
                                Image Uploading ...
                            </span>
                            @error('story_photo')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn bg-gradient-primary">Upload
                                <span wire:loading wire:target="uploadStory">
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <div>
        <div class="modal fade" id="view-story-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{$stories->user->first_name ?? "User"}}</h5>
                        &nbsp;&nbsp;&nbsp;
                        <span style="font-size: 10px;">{{$stories['created_at'] ?? ""}}</span>
                        <button type="button" wire:click="$emit('viewStoryModal')" class="close btn btn-close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex content-center">
                            <img class="w-100" src="{{$stories['upload_url']['url'] ?? null}}" alt="Story">
                        </div>
                    </div>
                    @if($stories && $stories['user_id'] === \App\Helpers\Helpers::getWebUser()->id)
                        <div class="modal-footer">
                            <button wire:click="deleteStory({{$stories['id'] ?? null}})" class="btn btn-round btn-danger"><i class="fas fa-trash"></i></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        window.livewire.on('toggleCreateStoryFormModal', () => $('#create_story').click());
        // window.livewire.on('toggleViewStoryFormModal', () => $('#view-story-modal').modal('toggle'));
        window.livewire.on('hideSuccessAlert', () => { setTimeout(function (){
            $('.alert-success').fadeOut('fast');
        }, 3000)})
    </script>
@endpush
