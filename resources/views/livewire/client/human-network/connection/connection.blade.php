<style>
    @media (min-width: 360px) and (max-width: 991px) {

        .connectionDev
        {
            width: 100%;
            margin-top: 5px;

        }

        .connectionBtn
        {
            width: 100%;
        }

        .searchInput
        {
            margin-top: 10px;
        }

    }

    @media (min-width: 991px) and (max-width: 2560px) {

        .profileCard
        {
            display: flex;
            justify-content: center;
        }
    }
</style>
<div>

    <div class="container  pt-3">

        <div class="row">

            <div class="col-12 col-md-6 nav-tab  ">

                <div class="nav nav-tabs border-0 " id="myTab" role="tablist" style="max-width: fit-content;">
                    <div class="nav-item connectionDev" role="presentation">
                        <button class="connectionBtn rainbow-border-user-nav-btn  me-2   mt-2 mt-md-0 rounded-1 updateBtn {{request()->input('type', 'connection') === "connection" ? "active" : ""}}" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane"

                                aria-selected="true">Connections</button>
                    </div>

                    <div class="nav-item connectionDev" role="presentation">
                        <button class="connectionBtn rainbow-border-user-nav-btn mt-2 mt-md-0 updateBtn rounded-1 {{request()->input('type', 'connection') === "request" ? "active" : ""}}" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile-tab-pane" type="button" role="tab"

                                aria-controls="profile-tab-pane" aria-selected="false">Connection Request</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">

                <div class="row mt-1">

                    <div class="col-12">
                        <div class="input-group ms-md-4 pe-md-4 searchInput">
{{--                     <span style="background-color: #0f1534;color: white;" class="input-group-text">--}}
{{--                         <i class="fas fa-search" aria-hidden="true"></i>--}}
{{--                     </span>--}}
                            <input type="text" wire:model.debounce="search_connection_name"
                                   class="beige-background-color search-bar" style="border: 1px solid #f2661c !important;
                           padding: 5px; width: 100%; border-radius: 5px;"
                                   placeholder="Search user to connect">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-10">
                        <button class="rainbow-border-user-nav-btn float-end mt-4 mb-4 clickBtn"
                                data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"
                                style="padding: 5px 14px 5px 14px; border-radius: 7px;">
                            Advance Filters
                        </button>
                    </div>

                </div>

            </div>

        </div>
        <div class="row pt-1">
            <div class="collapse" id="collapseExample" wire:ignore.self>
                <div class="float-end w-100">

                    <div class="row">

                        <div class="col-2"></div>

                        <div class="col-lg-5 col-12">
                            <select class="form-control" style="background-color: #f3deba;width: 90%;margin-top: 5px" wire:model="style_code">
                                <option value="">Search by style and feature</option>
                                @foreach($style_feature_color_codes as $code)
                                    <option value="{{$code->code}}">{{$code->public_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-5 col-12">
                            <select wire:model="alchemy_code" class="form-control" style="background-color: #f3deba;width: 90%;margin-top: 5px">
                                <option value="">Search by alchemy code</option>
                                @foreach($alchemy_color_codes as $code)
                                    <option value="{{$code->code}}">{{$code->public_name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="row ">

            <div class="col-12 mx-auto">

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade pt-3 {{request()->input('type', 'connection') === "connection" ? "show active" : ""}}" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

                        @empty($users[0])
                            <p class="text-white">No user found</p>
                        @endempty

                    <div class="row  pt-2 ">

                        @foreach($users as $user)
                            <div class="col-6 col-md-6 col-lg-4 col-xxl-3 d-flex justify-content-center py-3">

                                <div class="text-center shadow-sm connection-card" style="width: 17rem; height: 17rem; padding:0; border-radius: 8px;">

                                    <div class="card-body d-flex flex-column justify-content-end" style="height: 40%; padding: 0;">
                                        <div class="card-img flex-grow-5">
                                            <img src="{{$user['photo_url']['url']}}" alt="profile pic"
                                                 style="width: 100px; height: 100px; border-radius: 100%; margin:-14px 10%; cursor: pointer; justify-content: center;">
                                        </div>
                                        <h5 class="card-title" style="padding-top:10%; padding-bottom: 10%; cursor:pointer; color: white;font-weight: 700;">
                                            {{$user->first_name . ' ' . $user->last_name}}
                                        </h5>
                                        <div class="profileCard" style="color: rgb(74, 74, 74);padding:5px 0px; border-top: 1px solid black;">

                                            <div class="p-1">
                                                @if($user['connection_status'] === 0)
                                                    <a class="rainbow-border-user-connect-btn btn-sm" wire:click="connectUnConnectUser({{$user->id}},'connect')" style=' font-size: x-small; font-weight: 900;'>Connect</a>
                                                @elseif($user['connection_status'] === 1)
                                                    <a class="rainbow-border-user-connect-btn updateBtn btn-sm" wire:click="connectUnConnectUser({{$user->id}},'un-connect')" style="font-size: x-small; font-weight: 900;">Connected</a>
                                                @elseif($user['connection_status'] === 2)
                                                    <a class="rainbow-border-user-connect-btn btn-secondary btn-sm" wire:click="connectUnConnectUser({{$user->id}},'un-connect')" style="font-size: x-small; font-weight: 900;">Pending</a>
                                                @elseif($user['connection_status'] === 3)
                                                    <a class="rainbow-border-user-connect-btn btn-success btn-sm" wire:click="connectUnConnectUser({{$user->id}},'accept')" style="font-size: x-small; font-weight: 900;">Accept</a>
                                                @endif
                                            </div>

                                            <div class="p-1">
                                                @if($user['is_follow'])
                                                    <a class="rainbow-border-user-nav-btn btn-secondary btn-sm" wire:click="followUser({{$user->id}},'connect')" style="font-size: x-small; font-weight: 900;">Following</a>
                                                @else
                                                    <a class="rainbow-border-user-nav-btn updateBtn btn-sm" wire:click="followUser({{$user->id}},'un-connect')" style='font-size: x-small; font-weight: 900;'>Follow</a>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                            @if($users->hasMorePages())
                                <button class="rainbow-border-user-nav-btn"  wire:click.prevent="loadMore"
                                        style=" width: 60%; margin: auto; font-weight: 600;">
                                    Load more
                                </button>
                            @endif

                        </div>

                    </div>
                    <div class="tab-pane fade pt-3 {{request()->input('type', 'connection') === "request" ? "active show" : ""}}" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

                        @empty($connection_requests[0])
                            <p style="color: #0f1535;font-size: 20px; font-weight: bold">No connection request</p>
                        @endempty

                        <div class="row">

                            @foreach($connection_requests as $connection_request)

                                <div class="col-3 col-sm-1 col-md-4 col-lg-4 col-xl-3 pt-3">

                                    <div class="text-center shadow-sm connection-card" style="width: 17rem; height: 17rem; padding:0; border-radius: 8px;">
                                        <div class="card-body d-flex flex-column justify-content-end" style="height: 40%; padding: 0;">
                                            <div class="card-img flex-grow-5">
                                                <img src="{{$connection_request['user']['photo_url']['thumbnail_url'] ?? null}}" alt=""
                                                     style="width: 100px; height: 100px; border-radius: 100%; margin:-14px 10%; cursor: pointer; justify-content: center;">
                                            </div>
                                            <h5 class="card-title" style="padding-top:10%; padding-bottom: 10%; cursor:pointer; color: white; font-weight: 700;">
                                                {{$connection_request->user ? $connection_request->user->first_name . ' ' . $connection_request->user->last_name : ""}}
                                            </h5>
                                            <div class="d-flex flex-row justify-content-center flex-1" style="color: rgb(74, 74, 74);padding:5px 0px; border-top: 1px solid black;">

                                                <div class="p-1">
                                                    <a class="btn btn-success" wire:click="connectUnConnectUser({{$connection_request->user->id ?? null}},'accept')" style="font-size: x-small; font-weight: 900;">Accept</a>
                                                </div>

                                                <div class="p-1">
                                                    <a class="btn btn-secondary" wire:click="connectUnConnectUser({{$connection_request->user->id ?? null}},'un-connect')" style="font-size: x-small; font-weight: 900;">Cancel</a>
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
