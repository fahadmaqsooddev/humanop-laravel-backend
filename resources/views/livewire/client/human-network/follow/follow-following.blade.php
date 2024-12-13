
<div>
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

        .connection-btn{
            color: #FFFFFF !important;
            background-color: #F95520 !important;
            cursor: pointer !important;
            border-radius: 10px !important;
            border: 0px !important;
            padding: 5px;
        }
        .updateBtn {
            background-color: #f2661c;
            color: white;
        }

        .updateBtn:hover {
            background-color: #f2661c;
            color: white;
        }



        @media (min-width: 360px) and (max-width: 991px) {

            .connectionDev {
                width: 100%;
                margin-top: 5px;

            }

            .connectionBtn {
                width: 100%;
            }

            .searchInput {
                margin-top: 20px;
            }

        }
        #search-bar::placeholder { /* Standard */
            color: #F95520 !important;
        }
    </style>
    <div class="container-fluid pt-3">
        <div class="row position-relative mb-4 mt-2 mx-1"
             style="background: #8BB1AB;border-radius: 40px !important;">
            <div class="row my-4" style="z-index: 11;">
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
        <div class="row suggestion_user_container" style="margin-left: 1.5rem !important;margin-right:0.1rem !important; ">

            <div class="col-12 col-md-8 my-auto" style="background:#F6BA81;border-radius: 25px;height: auto" >
                <div class="d-flex justify-content-around" style="height: 50px">
                    <div class="w-80 px-3 my-auto py-1" style="background: #F4ECE0;border-radius: 20px;display: flex;align-items: center">
                        <img src="{{asset('assets/new-design/icon/dashboard/search.svg')}}" height="15" width="15">
{{--                        <input type="text" wire:model.debounce="follower_search"--}}
{{--                               class="beige-background-color search-bar" style="border: 1px solid #f2661c !important;--}}
{{--                           padding: 5px; width: 100%; border-radius: 5px;"--}}
{{--                               placeholder="Search">--}}
                        <input type="text" wire:model.debounce="follower_search"
                               class="search-bar bg-transparent" id="search-bar" style="border: 0px !important;"
                               placeholder="Search Following Users">
                    </div>
                    <div class="my-auto" style="color: #F95520">
                        Filter
                    </div>
                    <div class="clickBtn  my-auto" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <img src="{{asset('assets/new-design/icon/dashboard/filter_setting.svg')}}" alt="notification"
                             height="15" width="15" >
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 nav-tab">
                <div class="nav nav-tabs border-0 " id="myTab" role="tablist" style="max-width: fit-content;">
                    <div class="nav-item connectionDev" role="presentation">
                        <button class="py-2 px-3 connectionBtn  bg-transparent   me-2 mt-2 mt-md-0 rounded-1 updateBtn {{request()->input('type', 'follower') === "follower" ? "active" : ""}}"
                                id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane"

                                aria-selected="true" style="border: 1px solid #F95520;color: #F95520;border-radius: 25px !important;;">Followers</button>
                    </div>

                    <div class="nav-item connectionDev" role="presentation">
                        <button class="py-2  bg-transparent connectionBtn n mt-2 mt-md-0 updateBtn rounded-1 {{request()->input('type', 'follower') === "following" ? "active" : ""}}"
                                id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile-tab-pane" type="button" role="tab"

                                aria-controls="profile-tab-pane" aria-selected="false" style="border: 1px solid #F95520;color: #F95520;border-radius: 25px !important;padding: 20px">Following</button>
                    </div>
                </div>

            </div>

        </div>
        <hr style="color: #F95520" class="mt-3">

{{--        <div class="row">--}}
{{--            <div class="col-12 col-md-6 nav-tab  ">--}}
{{--                <div class="nav nav-tabs border-0 " id="myTab" role="tablist" style="max-width: fit-content;">--}}
{{--                    <div class="nav-item connectionDev" role="presentation">--}}
{{--                        <button--}}
{{--                            class="connectionBtn rainbow-border-user-nav-btn  me-2   mt-2 mt-md-0 rounded-1 updateBtn {{request()->input('type', 'follower') === "follower" ? "active" : ""}}"--}}
{{--                            id="home-tab" data-bs-toggle="tab"--}}
{{--                            data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane"--}}

{{--                            aria-selected="true">Followers--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <div class="nav-item connectionDev" role="presentation">--}}
{{--                        <button--}}
{{--                            class="connectionBtn rainbow-border-user-nav-btn mt-2 mt-md-0 updateBtn rounded-1 {{request()->input('type', 'follower') === "following" ? "active" : ""}}"--}}
{{--                            id="profile-tab" data-bs-toggle="tab"--}}
{{--                            data-bs-target="#profile-tab-pane" type="button" role="tab"--}}

{{--                            aria-controls="profile-tab-pane" aria-selected="false">Following--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-12 col-md-6">--}}
{{--                <div class="input-group ms-md-4 pe-md-4 searchInput">--}}
{{--                    <input type="text" wire:model.debounce="follower_search"--}}
{{--                           class="beige-background-color search-bar" style="border: 1px solid #f2661c !important;--}}
{{--                           padding: 5px; width: 100%; border-radius: 5px;"--}}
{{--                           placeholder="Search">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="row">
            <div class="col-12">
                <div class="tab-content" id="myTabContent">
                    <div
                        class="tab-pane fade pt-3 {{request()->input('type', 'follower') === "follower" ? "show active" : ""}}"
                        id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                        @empty($followers[0])
                            <p style="color: #0f1535; font-size: 20px; font-weight: bold">No follower</p>
                        @endempty
                        <div class="row">
                            @foreach($followers as $follow)
                                <div class="col-lg-8 col-sm-12 col-md-8 pt-3">
                                    <div class="w-100 d-flex bg-transparent " style="height: 80px;border-radius: 20px;border:1px solid #F95520">
                                        <div class="d-flex my-auto justify-content-start w-80">
                                            <div class="my-auto mx-3">
                                                <img src="{{$follow['user']['photo_url']['thumbnail_url'] ?? null}}" alt=""
                                                     style="width: 60px; height: 60px; border-radius: 100%; margin:-14px 10%; cursor: pointer; justify-content: center;">
                                            </div>
                                            <div>
                                                <h6 class="card-title mt-1 mb-0" style="cursor:pointer; color: #1E1D1D;font-weight: 700;">
                                                    {{$follow->user ? $follow->user->first_name . ' ' . $follow->user->last_name : ""}}
                                                </h6>
                                            </div>
                                        </div>
                                        <div class="d-flex my-auto w-20 mx-auto">
                                            <div class="mx-2">
                                                <a  wire:click="connectUnConnectUser({{$connection_request->user->id ?? null}},'accept')" style="cursor: pointer">
                                                    <img src="{{asset('assets/new-design/icon/connection/follow_message.svg')}}" height="30" width="30">
                                                </a>
                                            </div>
                                            <div class="mt-1">
                                                @if($follow['user']['is_follow'] ?? false)
                                                    <a class="connection-btn"
                                                       wire:click="followUser({{$follow->user->id ?? null}})"
                                                       style="font-size: small; font-weight: 600;">Remove</a>
                                                @else
                                                    <a class="updateBtn connection-btn"
                                                       wire:click="followUser({{$follow->user->id ?? null}})"
                                                       style='font-size: small; font-weight: 600;'>Follow</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
{{--                                <div class="col-sm-12 col-md-6 col-lg-4 col-xxl-3 d-flex justify-content-center py-3">--}}
{{--                                    <div class="text-center shadow-sm connection-card"--}}
{{--                                         style="width: 17rem; height: 17rem; padding:0; border-radius: 8px;">--}}
{{--                                        <div class="card-body d-flex flex-column justify-content-end"--}}
{{--                                             style="height: 40%; padding: 0;">--}}
{{--                                            <div class="card-img flex-grow-5">--}}
{{--                                                <img src="{{$follow['user']['photo_url']['thumbnail_url'] ?? null}}"--}}
{{--                                                     alt=""--}}
{{--                                                     style="width: 100px; height: 100px; border-radius: 100%; margin:-14px 10%; cursor: pointer; justify-content: center;">--}}
{{--                                            </div>--}}
{{--                                            <h5 class="card-title"--}}
{{--                                                style="padding-top:10%; padding-bottom: 10%; cursor:pointer; color: white;font-weight: 700;">--}}
{{--                                                {{$follow->user ? $follow->user->first_name . ' ' . $follow->user->last_name : ""}}--}}
{{--                                            </h5>--}}
{{--                                            <div class="d-flex flex-row justify-content-center flex-1"--}}
{{--                                                 style="color: rgb(74, 74, 74);padding:5px 0px; border-top: 1px solid black;">--}}

{{--                                                <div class="p-2">--}}
{{--                                                    @if($follow['user']['is_follow'] ?? false)--}}
{{--                                                        <a class="rainbow-border-user-nav-btn btn-secondary btn-sm"--}}
{{--                                                           wire:click="followUser({{$follow->user->id ?? null}})"--}}
{{--                                                           style="font-size: small; font-weight: 900;">Following</a>--}}
{{--                                                    @else--}}
{{--                                                        <a class="rainbow-border-user-nav-btn updateBtn btn-sm"--}}
{{--                                                           wire:click="followUser({{$follow->user->id ?? null}})"--}}
{{--                                                           style='font-size: small; font-weight: 900;'>Follow</a>--}}
{{--                                                    @endif--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            @endforeach
                        </div>
                    </div>
                    <div
                        class="tab-pane fade pt-3 {{request()->input('type', 'follower') === "following" ? "active show" : ""}}"
                        id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                        @empty($followings[0])
                            <p style="color: #0f1535;font-size: 20px; font-weight: bold">No following</p>
                        @endempty
                        <div class="row pt-2">
                            @foreach($followings as $following)
                                <div class="col-lg-8 col-sm-12 col-md-8 pt-3">
                                    <div class="w-100 d-flex bg-transparent " style="height: 80px;border-radius: 20px;border:1px solid #F95520">
                                        <div class="d-flex my-auto justify-content-start w-80">
                                            <div class="my-auto mx-3">
                                                <img src="{{$following['follower']['photo_url']['thumbnail_url'] ?? null}}" alt=""
                                                     style="width: 60px; height: 60px; border-radius: 100%; margin:-14px 10%; cursor: pointer; justify-content: center;">
                                            </div>
                                            <div>
                                                <h6 class="card-title mt-1 mb-0" style="cursor:pointer; color: #1E1D1D;font-weight: 700;">
                                                    {{$following->follower ? $following->follower->first_name . ' ' . $following->follower->last_name : ""}}
                                                </h6>
                                            </div>
                                        </div>
                                        <div class="d-flex my-auto w-20 mx-auto">
                                            <div class="mx-2">
                                                <a  wire:click="connectUnConnectUser({{$connection_request->user->id ?? null}},'accept')" style="cursor: pointer">
                                                    <img src="{{asset('assets/new-design/icon/connection/follow_message.svg')}}" height="30" width="30">
                                                </a>
                                            </div>
                                            <div class="mt-1">
                                                @if($following['follower']['is_follow'] ?? false)
                                                    <a class="connection-btn"
                                                       wire:click="followUser({{$following->follower->id ?? null}})"
                                                       style="font-size: small; font-weight: 600;">Unfollow</a>
                                                @else
                                                    <a class="updateBtn connection-btn"
                                                       wire:click="followUser({{$following->follower->id ?? null}})"
                                                       style='font-size: small; font-weight: 600;'>Follow</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>



{{--                                <div class="col-sm-12 col-md-6 col-lg-4 col-xxl-3 d-flex justify-content-center py-3">--}}
{{--                                    <div class="text-center shadow-sm connection-card"--}}
{{--                                         style="width: 17rem; height: 17rem; padding:0; border-radius: 8px;">--}}
{{--                                        <div class="card-body d-flex flex-column justify-content-end"--}}
{{--                                             style="height: 40%; padding: 0;">--}}
{{--                                            <div class="card-img flex-grow-5">--}}
{{--                                                <img--}}
{{--                                                    src="{{$following['follower']['photo_url']['thumbnail_url'] ?? null}}"--}}
{{--                                                    alt=""--}}
{{--                                                    style="width: 100px; height: 100px; border-radius: 100%; margin:-14px 10%; cursor: pointer; justify-content: center;">--}}
{{--                                            </div>--}}
{{--                                            <h5 class="card-title"--}}
{{--                                                style="padding-top:10%; padding-bottom: 10%; cursor:pointer; color: white;font-weight: 700;">--}}
{{--                                                {{$following->follower ? $following->follower->first_name . ' ' . $following->follower->last_name : ""}}--}}
{{--                                            </h5>--}}
{{--                                            <div class="d-flex flex-row justify-content-center flex-1"--}}
{{--                                                 style="color: rgb(74, 74, 74);padding:5px 0px; border-top: 1px solid black;">--}}
{{--                                                <div class="p-2">--}}
{{--                                                    @if($following['follower']['is_follow'] ?? false)--}}
{{--                                                        <a class="rainbow-border-user-nav-btn btn-secondary btn-sm"--}}
{{--                                                           wire:click="followUser({{$following->follower->id ?? null}})"--}}
{{--                                                           style="font-size: small; font-weight: 900;">Following</a>--}}
{{--                                                    @else--}}
{{--                                                        <a class="rainbow-border-user-nav-btn updateBtn btn-sm"--}}
{{--                                                           wire:click="followUser({{$following->follower->id ?? null}})"--}}
{{--                                                           style='font-size: small; font-weight: 900;'>Follow</a>--}}
{{--                                                    @endif--}}
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
