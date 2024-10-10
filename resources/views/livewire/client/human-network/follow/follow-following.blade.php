<div>

    <div class="container pt-3">

        <div class="row">

            <div class="col-12 col-md-6 nav-tab ">

                <div class="nav nav-tabs border-0" id="myTab" role="tablist" style="max-width: max-content;">
                    <div class="nav-item" role="presentation">
                        <button class="rainbow-border-user-nav-btn me-2 updateBtn {{request()->input('type', 'follower') === "follower" ? "active" : ""}}" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane"
                                style='background-color: #f2661c;color: white;'
                                aria-selected="true">Followers</button>
                    </div>
                    <div class="nav-item" role="presentation">
                        <button class="rainbow-border-user-nav-btn updateBtn {{request()->input('type', 'follower') === "following" ? "active" : ""}}" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile-tab-pane" type="button" role="tab"
                                style="background-color: #f2661c;color: white;"
                                aria-controls="profile-tab-pane" aria-selected="false">Following</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">

                <div class="input-group ms-md-4 pe-md-4">
                     <span style="border-color: white" class="input-group-text">
                         <i class="fas fa-search" aria-hidden="true"></i>
                     </span>
                    <input type="text" wire:model.debounce="follower_search"
                           class="" style="background-color: transparent; color: white;border: 1px solid white;
                           border-left: 0px; padding: 5px; width: 75%; border-radius: 0 5px 5px 0;"
                           placeholder="Search Follower">
                </div>

{{--                <input type="search" wire:model="follower_search" class="form-control" placeholder="Find {{request()->input('type', 'follower')}}">--}}
{{--                <label class="form-label" for="datatable-search-input">Search</label>--}}

            </div>

        </div>

        <div class="row">

            <div class="col-12">

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade pt-3 {{request()->input('type', 'follower') === "follower" ? "show active" : ""}}" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

                        @empty($followers[0])
                            <p class="text-white">No follower...</p>
                        @endempty

                        <div class="row">

                            @foreach($followers as $follow)

                                <div class="col-sm-12 col-md-6 col-lg-4 col-xxl-3 d-flex justify-content-center py-3">

                                    <div class="card text-center shadow-sm" style="width: 17rem; height: 17rem; padding:0; border-radius: 8px;background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%) border-box;">
                                        <div class="card-body d-flex flex-column justify-content-end" style="height: 40%; padding: 0;">
                                            <div class="card-img flex-grow-5">
                                                <img src="{{$follow['user']['photo_url']['thumbnail_url'] ?? null}}" alt=""
                                                     style="width: 100px; height: 100px; border-radius: 100%; margin:-14px 10%; cursor: pointer; justify-content: center;">
                                            </div>
                                            <h5 class="card-title" style="padding-top:10%; padding-bottom: 10%; cursor:pointer; color: white;font-weight: 700;">
                                                {{$follow->user ? $follow->user->first_name . ' ' . $follow->user->last_name : ""}}
                                            </h5>
                                            <div class="d-flex flex-row justify-content-center flex-1" style="color: rgb(74, 74, 74);padding:5px 0px; border-top: 1px solid black;">

                                                <div class="p-2">
                                                    @if($follow['user']['is_follow'] ?? false)
                                                        <a class="rainbow-border-user-nav-btn btn-secondary btn-sm" wire:click="followUser({{$follow->user->id ?? null}})" style="font-size: small; font-weight: 900;">Following</a>
                                                    @else
                                                        <a class="rainbow-border-user-nav-btn updateBtn btn-sm" wire:click="followUser({{$follow->user->id ?? null}})" style='font-size: small; font-weight: 900;'>Follow</a>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                {{--                            <h1>--}}
                                {{--                                {{$follow->user ? $follow->user->first_name . ' ' . $follow->user->last_name : ""}}--}}
                                {{--                            </h1>--}}
                            @endforeach

                        </div>
                    </div>
                    <div class="tab-pane fade pt-3 {{request()->input('type', 'follower') === "following" ? "active show" : ""}}" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

                        @empty($followings[0])
                            <p class="text-white">No following</p>
                        @endempty

                        <div class="row pt-2">

                            @foreach($followings as $following)

                                <div class="col-sm-12 col-md-6 col-lg-4 col-xxl-3 d-flex justify-content-center py-3">

                                    <div class="card text-center shadow-sm" style="width: 17rem; height: 17rem; padding:0; border-radius: 8px;background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%) border-box;">
                                        <div class="card-body d-flex flex-column justify-content-end" style="height: 40%; padding: 0;">
                                            <div class="card-img flex-grow-5">
                                                <img src="{{$following['follower']['photo_url']['thumbnail_url'] ?? null}}" alt=""
                                                     style="width: 100px; height: 100px; border-radius: 100%; margin:-14px 10%; cursor: pointer; justify-content: center;">
                                            </div>
                                            <h5 class="card-title" style="padding-top:10%; padding-bottom: 10%; cursor:pointer; color: white;font-weight: 700;">
                                                {{$following->follower ? $following->follower->first_name . ' ' . $following->follower->last_name : ""}}
                                            </h5>
                                            <div class="d-flex flex-row justify-content-center flex-1" style="color: rgb(74, 74, 74);padding:5px 0px; border-top: 1px solid black;">

{{--                                                <div class="p-2">--}}
{{--                                                    @if($user['connection_status'] === 0)--}}
{{--                                                        <a class="btn updateBtn" wire:loading wire:target="connectUnConnectUser" wire:click="connectUnConnectUser({{$user->id}},'connect')" style='background-color: #f2661c;color: white;'>Connect</a>--}}
{{--                                                    @elseif($user['connection_status'] === 1)--}}
{{--                                                        <a class="btn btn-primary" wire:click="connectUnConnectUser({{$user->id}},'un-connect')">Connected</a>--}}
{{--                                                    @elseif($user['connection_status'] === 2)--}}
{{--                                                        <a class="btn btn-secondary" wire:click="connectUnConnectUser({{$user->id}},'un-connect')">Pending</a>--}}
{{--                                                    @elseif($user['connection_status'] === 3)--}}
{{--                                                        <a class="btn btn-success" wire:click="connectUnConnectUser({{$user->id}},'accept')">Accept</a>--}}
{{--                                                    @endif--}}
{{--                                                </div>--}}

                                                <div class="p-2">
                                                    @if($following['follower']['is_follow'] ?? false)
                                                        <a class="rainbow-border-user-nav-btn btn-secondary btn-sm" wire:click="followUser({{$following->follower->id ?? null}})" style="font-size: small; font-weight: 900;">Following</a>
                                                    @else
                                                        <a class="rainbow-border-user-nav-btn updateBtn btn-sm" wire:click="followUser({{$following->follower->id ?? null}})" style='font-size: small; font-weight: 900;'>Follow</a>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                            @endforeach
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
