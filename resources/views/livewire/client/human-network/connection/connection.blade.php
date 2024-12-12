@push('css')
    <style>
        @media (min-width: 360px) and (max-width: 991px) {

            .connectionDev {
                width: 100%;
                margin-top: 5px;

            }

            .connectionBtn {
                width: 100%;
            }

            .searchInput {
                margin-top: 10px;
            }

        }

        @media (min-width: 991px) and (max-width: 2560px) {

            .profileCard {
                display: flex;
                justify-content: center;
            }
        }

        .connection-btn {
            color: #FFFFFF !important;
            background-color: #F95520 !important;
            cursor: pointer !important;
            border-radius: 10px !important;
            border: 0px !important;
        }

        .linear_blue {
            background: linear-gradient(to bottom, #2594B7 2%, #B4CFCB 98%);
        }

        .linear_green {
            background: linear-gradient(to bottom, #84D0AC 2%, #DAEFE4 98%);
        }

        .linear_light_orange {
            background: linear-gradient(to bottom, #F8BA82 2%, #F1DAC4 98%);
        }

        .linear_orange {
            background: linear-gradient(to bottom, #ED7537 2%, #F4B493 98%);
        }

        #search-bar::-webkit-input-placeholder { /* Chrome, Safari, Opera */
            color: #F95520 !important;
        }

        #search-bar::-moz-placeholder { /* Firefox 19+ */
            color: #F95520 !important;
        }

        #search-bar:-ms-input-placeholder { /* IE 10+ */
            color: #F95520 !important;
        }

        #search-bar::-ms-input-placeholder { /* Microsoft Edge */
            color: #F95520 !important;
        }

        #search-bar::placeholder { /* Standard */
            color: #F95520 !important;
        }

        .search-bar:focus {
            border: none !important;
        }

    </style>
@endpush
<div>
    <div class="container-fluid pt-3">
        <div class="row position-relative mb-4 mt-2 mx-1"
             style="background: #8BB1AB;border-radius: 40px !important;">
            <div class="row my-4">
                <div class="col-md-6 my-auto col-lg-6 col-sm-12" style="padding-left: 30px">
                    <div class="d-flex ">
                        <div>
                            <img
                                src="{{ Auth::user()['photo_url']['url'] ?? URL::asset('assets/img/default-user-image.png') }}"
                                height="80" width="80" alt="profile_image"
                                class="shadow-sm  user_profile_image" style="border-radius: 50%">
                        </div>
                        <div style="margin-top: 12px">
                            <p class="mb-0 "
                               style="font-weight: bold;color: #F4ECE0;font-size: 18px;margin-left:10px">
                                Welcome Back {{Auth::user()['first_name']}} !</p>
                            @if(!empty(\App\Helpers\Helpers::getWebUser()['optional_trait']))
                                <p class="mb-0 font-weight-bold text-sm"
                                   style="color: white;margin-left:10px">
                                    Optimal Trait To Be In Right Now:
                                </p>
                                <h6 style="color: white;font-size: 18px;margin-left:10px; cursor:pointer;"
                                    onclick="goToProfileOverviewPage('{{\App\Helpers\Helpers::getWebUser()['optional_trait'][2]}}','style_{{\App\Helpers\Helpers::getWebUser()['optional_trait'][0]}}')">
                                    <strong>{{ \App\Helpers\Helpers::getWebUser()['optional_trait'][0] }}</strong>
                                </h6>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-lg-6 my-auto col-sm-12">
                    <div class="d-flex justify-content-around px-4">
                        <button class="bg-transparent text-center py-2"
                                style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px;width: 48%"
                                data-bs-toggle="modal"
                                data-bs-target="#qrCodeModal"
                        >
                            Get free pro version
                        </button>
                        @if(\App\Helpers\Helpers::getWebUser()->is_admin == \App\Enums\Admin\Admin::IS_ADMIN || \App\Helpers\Helpers::getWebUser()->is_admin == \App\Enums\Admin\Admin::SUB_ADMIN)

                            <a href="{{route('assessments')}}"
                               class="bg-transparent  position-relative text-center py-2 px-4"
                               style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px;">
                                Access your results
                                <div class="position-absolute"
                                     style="right: -10px;top: -16px;height: 36px;width: 36px;background: #8BB1AB;padding-left: 0px;">
                                    <img src="{{asset('assets/new-design/icon/dashboard/Arrow.svg')}}"
                                         alt="notification" width="40" height="40">
                                </div>
                            </a>



                            {{--                                    <a href="{{route('assessments')}}" style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
                            {{--                                       class="btn-sm-1 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Access Latest Results--}}
                            {{--                                    </a>--}}

                        @elseif(\App\Helpers\Helpers::getWebUser()->assessments()->where('page', 0)->count() > 0)

                            @php
                                $userId = \App\Helpers\Helpers::getWebUser()['id'];

                                $assessment = \App\Models\Assessment::where('user_id', $userId)->where('page', 0)->latest()->first();

                            @endphp
                            @if(\App\Helpers\Helpers::getWebUser()['is_admin'] == 4)
                                {{--                                        <a href="{{route('practitioner_profile_overview', $assessment['id'])}}"--}}
                                {{--                                           style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
                                {{--                                           class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Access Latest Results--}}
                                {{--                                        </a>--}}


                                <a href="{{route('practitioner_profile_overview', $assessment['id'])}}"
                                   class="bg-transparent  position-relative text-center py-2 "
                                   style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px;width: 48%">
                                    Access your results
                                    <div class="position-absolute"
                                         style="right: -10px;top: -16px;height: 36px;width: 36px;background: #8BB1AB;padding-left: 0px;">
                                        <img src="{{asset('assets/new-design/icon/dashboard/Arrow.svg')}}"
                                             alt="notification" width="40" height="40">
                                    </div>
                                </a>
                            @elseif(\App\Helpers\Helpers::getWebUser()['practitioner_id'] != null)
                                {{--                                        <a href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('practitioner-client-profile-overview', ['id' => $assessment['id'] ]) }}"--}}
                                {{--                                           style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
                                {{--                                           class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Access Latest Results--}}
                                {{--                                        </a>--}}

                                <a href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('practitioner-client-profile-overview', ['id' => $assessment['id'] ]) }}"
                                   class="bg-transparent text-center position-relative py-2"
                                   style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px;width: 48%">
                                    Access your results
                                    <div class="position-absolute"
                                         style="right: -10px;top: -16px;height: 36px;width: 36px;background: #8BB1AB;padding-left: 0px;">
                                        <img src="{{asset('assets/new-design/icon/dashboard/Arrow.svg')}}"
                                             alt="notification" width="40" height="40">
                                    </div>
                                </a>
                            @else
                                {{--                                        <a href="{{route('user_profile_overview', $assessment['id'])}}"--}}
                                {{--                                           style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
                                {{--                                           class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Access Latest Results--}}
                                {{--                                        </a>--}}

                                <a href="{{route('user_profile_overview', $assessment['id'])}}"
                                   class="bg-transparent  text-center position-relative py-2 "
                                   style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px;width: 48%">
                                    Access your results
                                    <div class="position-absolute"
                                         style="right: -10px;top: -16px;height: 36px;width: 36px;background: #8BB1AB;padding-left: 0px;">
                                        <img src="{{asset('assets/new-design/icon/dashboard/Arrow.svg')}}"
                                             alt="notification" width="40" height="40">
                                    </div>
                                </a>

                            @endif

                        @else
                            <a class="bg-transparent text-center position-relative py-2 "
                               data-toggle="tooltip" data-placement="top" title="Take the assessment first"
                               style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px;width: 48%">
                                Access your results
                                <div class="position-absolute"
                                     style="right: -10px;top: -16px;height: 36px;width: 36px;background: #8BB1AB;padding-left: 0px;">
                                    <img src="{{asset('assets/new-design/icon/dashboard/Arrow.svg')}}"
                                         alt="notification" width="40" height="40">
                                </div>
                            </a>
                            {{--                                    <button--}}
                            {{--                                        style="padding: 10px 16px 10px 16px; border-radius: 7px; background-color: grey;"--}}
                            {{--                                        data-toggle="tooltip" data-placement="top" title="Take the assessment first"--}}
                            {{--                                        class="text-white btn-sm-2 btn-md-3 btn-lg-5  navButtonResponsive">Access Latest Results--}}
                            {{--                                    </button>--}}

                        @endif






                        {{--                                --}}
                        {{--                                <button class="bg-transparent w-70 py-2 position-relative"--}}
                        {{--                                        style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px">--}}
                        {{--                                    Access your results--}}
                        {{--                                    <div class="position-absolute"--}}
                        {{--                                         style="right: -10px;top: -16px;height: 36px;width: 36px;background: #FCB178;padding-left: 0px;">--}}
                        {{--                                        <img src="{{asset('assets/new-design/icon/dashboard/Arrow.svg')}}"--}}
                        {{--                                             alt="notification" width="40" height="40">--}}
                        {{--                                    </div>--}}
                        {{--                                </button>--}}

                    </div>
                </div>
            </div>
            <div class="position-absolute"
                 style="right: -10px;top: -25px;height: 60px;width: 60px;border-radius: 50%;background: #1C365E;padding-left: 5px;border: 10px solid #8BB1AB">
                <img src="{{asset('assets/new-design/icon/dashboard/bell.svg')}}" alt="notification"
                     width="30" height="40">
            </div>
            <div class="position-absolute"
                 style="left: 40%">
                <img src="{{asset('assets/new-design/icon/dashboard/header_badge.svg')}}" alt="notification"
                     style="width: 10%"
                     height="120">
            </div>
        </div>
        @if(request()->input('type') != 'request')
            <div class="row suggestion_user_container"
                 style="margin-left: 1.5rem !important;margin-right:0.1rem !important; ">
                <div class="col-12 col-md-7 my-auto" style="background:#F6BA81;border-radius: 25px;height: auto">
                    <div class="d-flex justify-content-around" style="height: 50px">
                        <div class="w-80 px-3 my-auto py-1"
                             style="background: #F4ECE0;border-radius: 20px; display: flex;align-items: center">
                            <img src="{{asset('assets/new-design/icon/dashboard/search.svg')}}" height="15" width="15">
                            <input type="text" wire:model.debounce="search_connection_name"
                                   class="search-bar bg-transparent" id="search-bar"
                                   style="border: 0px !important; width: auto;"
                                   placeholder="Search user to connect">
                        </div>
                        <div class="my-auto" style="color: #F95520">
                            Filter
                        </div>
                        <div class="clickBtn  my-auto" data-toggle="collapse" data-target="#collapseExample"
                             aria-expanded="false" aria-controls="collapseExample">
                            <img src="{{asset('assets/new-design/icon/dashboard/filter_setting.svg')}}"
                                 alt="notification"
                                 height="15" width="15">
                        </div>
                    </div>
                    <div class="collapse mx-2" id="collapseExample" wire:ignore.self>
                        <div class="w-100 mb-2">

                            <div class="row mx-1 justify-content-between">

                                <div class="col-lg-6 col-12 d-flex "
                                     style="width: 49%;background-color: #F4ECE0;border-radius: 20px;border: 0px">
                                <span class="my-auto">
                                           <img src="{{asset('assets/new-design/icon/dashboard/search.svg')}}"
                                                height="15" width="15">
                                </span>
                                    <select class="form-control"
                                            style="background-color: #F4ECE0;border-radius: 20px;border: 0px"
                                            wire:model="style_code">
                                        <option value="">Search by style and feature</option>
                                        @foreach($style_feature_color_codes as $code)
                                            <option value="{{$code->code}}">{{$code->public_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 col-12 d-flex "
                                     style="width: 49%;background-color: #F4ECE0;border-radius: 20px;border: 0px">
                                 <span class="my-auto">
                                           <img src="{{asset('assets/new-design/icon/dashboard/search.svg')}}"
                                                height="15" width="15">
                                </span>
                                    <select wire:model="alchemy_code" class="form-control"
                                            style="background-color: #F4ECE0;border-radius: 20px;border: 0px">
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
                <div class="col-12 col-md-5 nav-tab">
                    <div class="nav nav-tabs border-0 " id="myTab" role="tablist" style="max-width: fit-content;">
                        <div class="nav-item connectionDev" role="presentation">
                            <button
                                class="py-2 px-3 connectionBtn  bg-transparent   me-2 mt-2 mt-md-0 rounded-1 updateBtn {{request()->input('type', 'connection') === "connection" ? "active" : ""}}"
                                id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane"
                                aria-selected="true"
                                style="border: 1px solid #F95520;color: #F95520;border-radius: 25px !important;;">
                                Connections
                            </button>
                        </div>

                        <div class="nav-item connectionDev" role="presentation">
                            <button
                                class="py-2  bg-transparent connectionBtn n mt-2 mt-md-0 updateBtn rounded-1 {{request()->input('type', 'connection') === "request" ? "active" : ""}}"
                                id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile-tab-pane" type="button" role="tab"

                                aria-controls="profile-tab-pane" aria-selected="false"
                                style="border: 1px solid #F95520;color: #F95520;border-radius: 25px !important;">
                                Connection Requests
                            </button>
                        </div>
                    </div>

                </div>

            </div>
            <div class="row mt-3 suggestion_user_container">
                <div class="col-1 pt-1">
                    <span style="color: #F95520"> <strong>Suggestions</strong></span>
                </div>
                <div class="col-11" style="padding-left: 20px">
                    <hr style="color: #F95520" class="mt-3">
                </div>
            </div>
            <div class="row mt-3 request_user_container" style="display: none">
                <div class="col-12 pt-1">
                    <h4 style="color: #F95520"><strong>Connection Requests:</strong></h4>
                </div>
            </div>
        @else
            <div class="row mt-3 request_user_container">
                <div class="col-12 pt-1">
                    <h4 style="color: #F95520"><strong>Connection Requests:</strong></h4>
                </div>
            </div>
        @endif

        <div class="row ">

            <div class="col-12 mx-auto">

                <div class="tab-content" id="myTabContent">
                    <div
                        class="tab-pane fade pt-3 {{request()->input('type', 'connection') === "connection" ? "show active" : ""}}"
                        id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

                        @empty($users[0])
                            <p class="text-white">No user found</p>
                        @endempty

                        <div class="row  pt-2 ">

                            @foreach($users as $user)
                                @php
                                    // Define your CSS classes
                                    $classes = ['linear_blue', 'linear_orange', 'linear_green', 'linear_light_orange'];
                                    // Select a random class
                                    $randomClass = $classes[array_rand($classes)];
                                @endphp
                                <div class="col-6 col-md-6 col-lg-3 col-xxl-3 d-flex justify-content-center py-3">

                                    <div class="text-center shadow-sm connection-card"
                                         style="width: 17rem; height: 17rem; padding:0; border-radius: 20px 20px 8px 8px;background-color: #FFFFFF !important">
                                        <div class="position-absolute w-100 {{$randomClass}}"
                                             style="height: 80px;border-radius: 20px">
                                        </div>

                                        <div class="card-body d-flex flex-column justify-content-end"
                                             style="height: 40%; padding: 0;">
                                            <div class="card-img flex-grow-5 position-absolute z-index-2"
                                                 style="top:30px;">
                                                <img src="{{$user['photo_url']['url']}}" alt="profile pic"
                                                     style="width: 70px; height: 70px; border-radius: 100%;  cursor: pointer; justify-content: center;">
                                                <h6 class="card-title mt-1 mb-0"
                                                    style="cursor:pointer; color: #1E1D1D;font-weight: 700;">
                                                    {{$user->first_name . ' ' . $user->last_name}}
                                                </h6>
                                                <p style="color: #1E1D1D"><small>Designer</small></p>
                                                <div class="d-flex justify-content-center">
                                                    <img src="{{asset('assets/new-design/icon/connection/like.svg')}}"
                                                         height="20" width="20"/>
                                                    <img
                                                        src="{{asset('assets/new-design/icon/connection/comment.svg')}}"
                                                        height="20" width="20" class="mx-3"/>
                                                    <img src="{{asset('assets/new-design/icon/connection/share.svg')}}"
                                                         height="20" width="20"/>
                                                </div>
                                            </div>

                                            <div class="profileCard mb-2"
                                                 style="color: rgb(74, 74, 74);padding:5px 0px;">
{{--                                                <div class="row">--}}
                                                    <div class="p-1 mt-2">
                                                        @if($user['connection_status'] === 0)
                                                            <a class="connection-btn btn-sm"
                                                               wire:click="connectUnConnectUser({{$user->id}},'connect')"
                                                               style=' font-size: x-small; font-weight: 900;'>Connect</a>
                                                        @elseif($user['connection_status'] === 1)
                                                            <a class="connection-btn updateBtn btn-sm"
                                                               wire:click="connectUnConnectUser({{$user->id}},'un-connect')"
                                                               style="font-size: x-small; font-weight: 900;">Connected</a>
                                                        @elseif($user['connection_status'] === 2)
                                                            <a class=" connection-btn  btn-sm"
                                                               wire:click="connectUnConnectUser({{$user->id}},'un-connect')"
                                                               style="font-size: x-small; font-weight: 900;">Pending</a>
                                                        @elseif($user['connection_status'] === 3)
                                                            <a class="connection-btn  btn-sm"
                                                               wire:click="connectUnConnectUser({{$user->id}},'accept')"
                                                               style="font-size: x-small; font-weight: 900;">Accept</a>
                                                        @endif
                                                    </div>
                                                    <div class="p-1 mt-2">
                                                        @if($user['is_follow'])
                                                            <a class="connection-btn btn-sm"
                                                               wire:click="followUser({{$user->id}},'connect')"
                                                               style="font-size: x-small; font-weight: 900;">Following</a>
                                                        @else
                                                            <a class="connection-btn updateBtn btn-sm"
                                                               wire:click="followUser({{$user->id}},'un-connect')"
                                                               style='font-size: x-small; font-weight: 900;'>Follow</a>
                                                        @endif
                                                    </div>
{{--                                                </div>--}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($users->hasMorePages())
                                <button class="connection-btn my-4" wire:click.prevent="loadMore"
                                        style=" width: 60%; margin: auto; font-weight: 600;">
                                    Load more
                                </button>
                            @endif

                        </div>

                    </div>
                    <div
                        class="tab-pane fade pt-3 {{request()->input('type', 'connection') === "request" ? "active show" : ""}}"
                        id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

                        @empty($connection_requests[0])
                            <p style="color: #0f1535;font-size: 20px; font-weight: bold">No connection request</p>
                        @endempty

                        <div class="row">

                            @foreach($connection_requests as $connection_request)
                                <div class="col-lg-8 col-sm-12 col-md-8 pt-3">
                                    <div class="w-100 d-flex bg-transparent "
                                         style="height: 80px;border-radius: 20px;border:1px solid #F95520">
                                        <div class="d-flex my-auto justify-content-start w-80">
                                            <div class="my-auto mx-3">
                                                <img
                                                    src="{{$connection_request['user']['photo_url']['thumbnail_url'] ?? null}}"
                                                    alt=""
                                                    style="width: 60px; height: 60px; border-radius: 100%; margin:-14px 10%; cursor: pointer; justify-content: center;">
                                            </div>
                                            <div>
                                                <h6 class="card-title mt-1 mb-0"
                                                    style="cursor:pointer; color: #1E1D1D;font-weight: 700;">
                                                    {{$connection_request->user ? $connection_request->user->first_name . ' ' . $connection_request->user->last_name : ""}}
                                                </h6>
                                            </div>
                                        </div>
                                        <div class="d-flex my-auto w-20 mx-auto">
                                            <div class="mx-2">
                                                <a wire:click="connectUnConnectUser({{$connection_request->user->id ?? null}},'accept')"
                                                   style="cursor: pointer">
                                                    <img src="{{asset('assets/new-design/icon/connection/accept.svg')}}"
                                                         height="30" width="30">
                                                </a>
                                            </div>
                                            <div>
                                                <a wire:click="connectUnConnectUser({{$connection_request->user->id ?? null}},'un-connect')"
                                                   style="cursor: pointer">
                                                    <img src="{{asset('assets/new-design/icon/connection/cancel.svg')}}"
                                                         height="30" width="30">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{--                                <div class="col-3 col-sm-1 col-md-4 col-lg-4 col-xl-3 pt-3">--}}

                                {{--                                    <div class="text-center shadow-sm connection-card" style="width: 17rem; height: 17rem; padding:0; border-radius: 8px;">--}}
                                {{--                                        <div class="card-body d-flex flex-column justify-content-end" style="height: 40%; padding: 0;">--}}
                                {{--                                            <div class="card-img flex-grow-5">--}}
                                {{--                                                <img src="{{$connection_request['user']['photo_url']['thumbnail_url'] ?? null}}" alt=""--}}
                                {{--                                                     style="width: 100px; height: 100px; border-radius: 100%; margin:-14px 10%; cursor: pointer; justify-content: center;">--}}
                                {{--                                            </div>--}}
                                {{--                                            <h5 class="card-title" style="padding-top:10%; padding-bottom: 10%; cursor:pointer; color: white; font-weight: 700;">--}}
                                {{--                                                {{$connection_request->user ? $connection_request->user->first_name . ' ' . $connection_request->user->last_name : ""}}--}}
                                {{--                                            </h5>--}}
                                {{--                                            <div class="profileCard" style="color: rgb(74, 74, 74);padding:5px 0px; border-top: 1px solid black;">--}}

                                {{--                                                <div class="p-1">--}}
                                {{--                                                    <a class="btn btn-success" wire:click="connectUnConnectUser({{$connection_request->user->id ?? null}},'accept')" style="font-size: x-small; font-weight: 900;">Accept</a>--}}
                                {{--                                                </div>--}}

                                {{--                                                <div class="p-1">--}}
                                {{--                                                    <a class="btn btn-secondary" wire:click="connectUnConnectUser({{$connection_request->user->id ?? null}},'un-connect')" style="font-size: x-small; font-weight: 900;">Cancel</a>--}}
                                {{--                                                </div>--}}

                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}

                                {{--                                </div>--}}

                            @endforeach

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
@push('js')
    <script>
        $('#profile-tab').on('click', function () {
            $('.suggestion_user_container').css('display', 'none');
            $('.request_user_container').css('display', 'flex');
        })
    </script>
@endpush
