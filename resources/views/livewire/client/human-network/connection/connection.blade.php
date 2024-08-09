<div>

    <div class="container pt-3">

        <div class="row">

            <div class="col-6">

                <ul class="nav nav-tabs" id="myTab" role="tablist" style="max-width: max-content;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link updateBtn {{request()->input('type', 'connection') === "connection" ? "active" : ""}}" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane"
                                style='background-color: #f2661c;color: white;'
                                aria-selected="true">Connections</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link updateBtn {{request()->input('type', 'connection') === "request" ? "active" : ""}}" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile-tab-pane" type="button" role="tab"
                                style="background-color: #f2661c;color: white;"
                                aria-controls="profile-tab-pane" aria-selected="false">Connection Request</button>
                    </li>
                </ul>
            </div>
            <div class="col-6">

                <input type="search" wire:model="search_connection_name" class="form-control" placeholder="Find connections">
                {{--                <label class="form-label" for="datatable-search-input">Search</label>--}}

            </div>

        </div>

        <div class="row">

            <div class="col-12">

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade pt-3 {{request()->input('type', 'connection') === "connection" ? "show active" : ""}}" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

                        @empty($users[0])
                            <p class="text-white">No user found</p>
                        @endempty

                        <div class="row pt-2">

                        @foreach($users as $user)
                            <div class="col-3 col-sm-1 col-md-4 col-lg-4 col-xl-3 pt-3">

                                <div class="card text-center shadow-sm" style="width: 17rem; height: 17rem; padding:0; border-radius: 8px;background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%) border-box;">
                                    <div class="card-body d-flex flex-column justify-content-end" style="height: 40%; padding: 0;">
                                        <div class="card-img flex-grow-5">
                                            <img src="{{$user->user_picture_url}}" alt=""
                                                 style="width: 100px; height: 100px; border-radius: 100%; margin:0 10%; cursor: pointer; justify-content: center;">
                                        </div>
                                        <h5 class="card-title" style="padding-top:10%; padding-bottom: 10%; cursor:pointer; color: white;font-weight: 700;">
                                            {{$user->first_name . ' ' . $user->last_name}}
                                        </h5>
                                        <div class="d-flex flex-row justify-content-center flex-1" style="color: rgb(74, 74, 74);padding:5px 0px; border-top: 1px solid black;">
{{--                                            @if($user['connection_status'] === 0)--}}
{{--                                                <button class="btn updateBtn" wire:loading wire:target="connectUnConnectUser" wire:click="connectUnConnectUser({{$user->id}},'connect')" style='background-color: #f2661c;color: white;'>Connect</button>--}}
{{--                                            @elseif($user['connection_status'] === 1)--}}
{{--                                                <button class="btn btn-primary" wire:click="connectUnConnectUser({{$user->id}},'un-connect')">Connected</button>--}}
{{--                                            @elseif($user['connection_status'] === 2)--}}
{{--                                                <button class="btn btn-secondary" wire:click="connectUnConnectUser({{$user->id}},'un-connect')">Pending</button>--}}
{{--                                            @elseif($user['connection_status'] === 3)--}}
{{--                                                <button class="btn btn-success" wire:click="connectUnConnectUser({{$user->id}},'accept')">Accept</button>--}}
{{--                                            @endif--}}

                                            <div class="p-2">
                                                @if($user['connection_status'] === 0)
                                                    <a class="btn" wire:loading wire:target="connectUnConnectUser" wire:click="connectUnConnectUser({{$user->id}},'connect')" style='border: 1px solid #f2661c; background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%) border-box; color: #f2661c; font-size: small; font-weight: 900;'>Connect</a>
                                                @elseif($user['connection_status'] === 1)
                                                    <a class="btn updateBtn" wire:click="connectUnConnectUser({{$user->id}},'un-connect')" style="background-color: #f2661c; color: white;font-size: small; font-weight: 900;">Connected</a>
                                                @elseif($user['connection_status'] === 2)
                                                    <a class="btn btn-secondary" wire:click="connectUnConnectUser({{$user->id}},'un-connect')" style="font-size: small; font-weight: 900;">Pending</a>
                                                @elseif($user['connection_status'] === 3)
                                                    <a class="btn btn-success" wire:click="connectUnConnectUser({{$user->id}},'accept')" style="font-size: small; font-weight: 900;">Accept</a>
                                                @endif
                                            </div>

{{--                                            <a href="#" class="btn"><img src="connect.png" style="width: 16px; height: 16px;" alt=""> Connection</a>--}}
{{--                                            <a href="#" class="btn"><img src="follow.png" style="width: 16px; height: 16px;" alt=""> Follow</a>--}}

                                            <div class="p-2">
                                                @if($user['is_follow'])
                                                    <a class="btn btn-secondary" wire:loading wire:target="connectUnConnectUser" wire:click="followUser({{$user->id}},'connect')" style="font-size: small; font-weight: 900;">Following</a>
                                                @else
                                                    <a class="btn updateBtn" wire:click="followUser({{$user->id}},'un-connect')" style='background-color: #f2661c;color: white;font-size: small; font-weight: 900;'>Follow</a>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
{{--                            <h1>--}}
{{--                                {{$user->first_name . ' ' . $user->last_name}}--}}
{{--                            </h1>--}}

                        @endforeach
                        </div>

                    </div>
                    <div class="tab-pane fade pt-3 {{request()->input('type', 'connection') === "request" ? "active show" : ""}}" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

                        @empty($connection_requests[0])
                            <p class="text-white">No connection request</p>
                        @endempty

                        <div class="row">

                            @foreach($connection_requests as $connection_request)

                                <div class="col-3 col-sm-1 col-md-4 col-lg-4 col-xl-3 pt-3">

                                    <div class="card text-center shadow-sm" style="width: 17rem; height: 17rem; padding:0; border-radius: 8px;background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%) border-box;">
                                        <div class="card-body d-flex flex-column justify-content-end" style="height: 40%; padding: 0;">
                                            <div class="card-img flex-grow-5">
                                                <img src="{{$user->user_picture_url}}" alt=""
                                                     style="width: 100px; height: 100px; border-radius: 100%; margin:0 10%; cursor: pointer; justify-content: center;">
                                            </div>
                                            <h5 class="card-title" style="padding-top:10%; padding-bottom: 10%; cursor:pointer; color: white; font-weight: 700;">
                                                {{$connection_request->user ? $connection_request->user->first_name . ' ' . $connection_request->user->last_name : ""}}
                                            </h5>
                                            <div class="d-flex flex-row justify-content-center flex-1" style="color: rgb(74, 74, 74);padding:5px 0px; border-top: 1px solid black;">

                                                <div class="p-2">
                                                    <a class="btn btn-success" wire:click="connectUnConnectUser({{$connection_request->user->id ?? null}},'accept')" style="font-size: small; font-weight: 900;">Accept</a>
                                                </div>

                                                {{--                                            <a href="#" class="btn"><img src="connect.png" style="width: 16px; height: 16px;" alt=""> Connection</a>--}}
                                                {{--                                            <a href="#" class="btn"><img src="follow.png" style="width: 16px; height: 16px;" alt=""> Follow</a>--}}

                                                <div class="p-2">
                                                    <a class="btn btn-secondary" wire:click="connectUnConnectUser({{$connection_request->user->id ?? null}},'un-connect')" style="font-size: small; font-weight: 900;">Cancel</a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                {{--                                <button class="btn btn-success" wire:click="connectUnConnectUser({{$connection_request->user_id}},'accept')">Accept</button>--}}

                            @endforeach

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
