@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])
<style>
    .description-container::-webkit-scrollbar {
        width: 10px;
        /* Width of the scrollbar */
    }

    .description-container::-webkit-scrollbar-track {
        background: rgb(160, 174, 192);
        /* Color of the track */
    }

    .description-container::-webkit-scrollbar-thumb {
        background-color: #888;
        /* Color of the handle */
        border-radius: 10px;
        /* Roundness of the handle */
        /*border: 2px solid #555; !* Space around the handle *!*/
    }

    /* Custom scrollbar for Firefox */
    .description-container {
        scrollbar-width: thin;
        /* Thickness of the scrollbar */
        scrollbar-color: #888 rgb(160, 174, 192);
        /* Color of the scrollbar and track */
    }

    .chat-card {
        background-color: rgb(27, 31, 86);
    }

    #chatDots {
        margin: 32px;
    }

    .chatDot {
        width: 10px;
        height: 10px;
        background-color: #f2661c;
        display: inline-block;
        margin: 1px;
        border-radius: 50%;
    }

    .chatDot:nth-child(1) {
        animation: bounce 1s infinite;
    }

    .chatDot:nth-child(2) {
        animation: bounce 1s infinite .2s;
    }

    .chatDot:nth-child(3) {
        animation: bounce 1s infinite .4s;
    }


    @keyframes bounce {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(8px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    .like,
    .dislike {
        display: inline-block;
        cursor: pointer;
        margin: 10px;
    }

    .dislike:hover,
    .like:hover {
        color: #f2661c;
        transition: all .2s ease-in-out;
        transform: scale(1.1);
    }

    .active {
        color: #f2661c;
    }

    .modal-close-btn {
        background: #f2661c;
        border: none;
        color: white;
        font-weight: bold;
        font-size: x-large;
        float: right;
        border-radius: 3px;
        padding: 0px 10px 1px 10px;
    }

    .pagination {
        float: right;
        margin-right: 24px;
    }

    .page-link {
        background: none !important;
    }

    .page-link:hover {
        background: #f2661c !important;
        color: white !important;
    }

    .page-item.active .page-link {
        background: #f2661c !important;
        color: white !important;
        border-color: #f2661c !important;
    }
</style>
@section('content')

    <div class="parent px-lg-5">
        <div class="container-fluid px-0 d-lg-none">
            <div class="page-header min-height-100 border-radius-xl">
            </div>
            <div class="card card-body blur shadow-blur  mt-n6 ">
                <div class="d-flex justify-content-between flex-wrap">

                    <div class="d-flex">
                        <div class="col-auto pb-sm-4">
                            <div class="avatar avatar-xl avatar-icon  ">
                                <img
                                    src="{{ Auth::user()['photo_url']['url'] ?? URL::asset('assets/img/default-user-image.png') }}"
                                    height="80" alt="profile_image" class="w-100 border-radius-lg shadow-sm  ">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="h-100">
                                <a href="{{route('user_profile_overview')}}">
                                    <h5 class="mb-1 text-white">
                                        {{Auth::user()['first_name']}} {{Auth::user()['last_name']}}
                                    </h5>
                                    <p class="mb-0 font-weight-bold text-sm text-white">
                                        Optimal Trait To Be In Right Now:
                                    </p>
                                    <p class="  text-white  word-break text-sm col-12">Perceptive Trait (Thinking) For
                                        Strategy and Problem
                                        Solving
                                        Activities</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column ">
                        <div class="nav nav-pills  nav-fill bg-transparent position-static   user-pannel-btn   "
                             role="tablist">
                            <div class="nav-item">
                                <button style="padding: 2px 16px 2px 16px; border-radius: 7px;background-color: #f2661c"
                                        class="text-white btn btn-sm-1 btn-md-3 btn-lg-5 ">Access Your<br> Results
                                </button>
                            </div>

                            <div class="nav-item">
                                <button style="padding: 2px 16px 2px 16px; border-radius: 7px;background-color: #f2661c"
                                        class=" ms-2 text-white btn btn-sm-2 btn-md-3 btn-lg-5 ">Get Free Pro<br>
                                    Access!
                                </button>
                            </div>
                        </div>
                    <!-- <div class="coins d-flex ">
                        <span class="fw-bold total-points "
                            style="color: #f2661c; text-shadow: 0 0 5px #f2661c, 0 0 10px #f2661c; margin-left: 25px;margin-top: 30px; ">100</span>
                        <img src="{{asset('assets/img/coins.gif')}}" alt="Coins falling"
                            style="width: 100px;height:100px;">
                        <span class="animated-number fade-in disappear"
                            style="color: orange; font-weight: 900; font-size: 2rem; text-shadow: orange 0px 0px 5px, orange 0px 0px 10px;">+1</span>
                    </div> -->
                    </div>

                </div>
            </div>
        </div>

        @if($admin_answer && !empty($admin_answer['question']))
            <div class="container-fluid p-2 mt-2">

                <div class="d-flex justify-content-between flex-row card card-body text-white gap-5">
                    <div class="" style="width: fit-content;cursor:pointer" data-bs-toggle="modal"
                         data-bs-target="#answerQueryModal">
                        <div>
                        <span style="color: #f2661c;font-size: 26px;font-weight: 800;display: flex;">
                            Your Query : {{$admin_answer['question']['query'] ?? null}}
                        </span>
                        </div>
                        <div>
                            <div class="text-white mt-2">
                                <span> Answer : </span>
                                @if(strlen($admin_answer->answer) > 270)

                                    {{substr($admin_answer->answer, 0, 265)}}

                                    &nbsp;&nbsp;
                                    <a href="javascript:void(0)" data-bs-toggle="modal"
                                       data-bs-target="#answerQueryModal" style="color: #f2661c;">read
                                        more...
                                    </a>

                                @else

                                    {{ $admin_answer->answer }}

                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-none d-lg-flex flex-column">
                        <div
                            class="nav nav-pills z-index-1 nav-fill bg-transparent position-static pb-5 user-pannel-btn "
                            role="tablist">
                            <div class="nav-item">
                                <button style="padding: 2px 16px 2px 16px; border-radius: 7px;background-color: #f2661c"
                                        class="text-white btn btn-sm-1 btn-md-3 btn-lg-5 ">Access Your<br> Results
                                </button>
                            </div>

                            <div class="nav-item">
                                <button style="padding: 2px 16px 2px 16px; border-radius: 7px;background-color: #f2661c"
                                        class=" ms-2 text-white btn btn-sm-2 btn-md-3 btn-lg-5 ">Get Free Pro<br>
                                    Access!
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    @endif

    <!-- main features section -->
        <div class="container-fluid px-0 ">
            <section class=""> {{-- py-3 --}}

                <div class="row">
                    <div class="mt-lg-4 mt-2 col-lg-3 col-sm-12 col-md-12 d-flex features-card">
                        <div class="col-lg-12  col-md-5 col-sm-12 mb-4 d-flex flex-column">
                            <div class="card" style="height: auto;">
                                <div class="card-body p-3" style="cursor: pointer;">
{{--                                     data-bs-toggle="modal" data-bs-target="#dailyTipModal">--}}
                                    <h5 class="text-white fs-10px">Daily Tip</h5>
                                    <div class="description-container" style="height: 275px;">

                                        {{$hide_button = false}}

                                        <p class="text-sm mt-3 fs-12px" style="color: rgb(160, 174, 192);">
                                            @if($tip && !empty($tip['description']))
                                                @if(strlen($tip['description']) > 250)
                                                    <span id="daily-tip-text">

                                                        {{$hide_button = true}}

                                                        {!! substr($tip['text'], 0, 305)!!}

                                                        <a href="javascript:void(0)" onclick="showDailyTipCompleteText(`{{$tip['description']}}`)" style="color: #f2661c;">read
                                                        more...
                                                    </a>
                                                    </span>
                                                    &nbsp;&nbsp;
{{--                                                    <a href="javascript:void(0)" data-bs-toggle="modal"--}}
{{--                                                       data-bs-target="#dailyTipModal" style="color: #f2661c;">read--}}
{{--                                                        more...--}}
{{--                                                    </a>--}}
                                                @else
                                                        {!! $tip['description'] !!}

                                                @endif
                                            @endif
                                        </p>

                                        @if($tip)
                                            <div>

                                                <div class="{{$hide_button ? "d-none" : "d-none"}} justify-content-center mt-2" id="read_all_tip">
                                                    <button style="background-color: #f2661c;" class="btn btn-sm text-white daily-tip-read-button"
                                                            {{$tip['is_read'] ?? null ? "disabled" : ""}}
                                                            onclick="onDailyTipAllRead()">
                                                        Complete Daily Tip
                                                    </button>
                                                </div>

                                            </div>
                                        @endif
                                    </div>

                                    @if($tip)
                                        <div>

                                            <div class="{{$hide_button ? "d-none" : "d-flex"}} justify-content-center mt-2">
                                                <button style="background-color: #f2661c;" class="btn btn-sm text-white daily-tip-read-button"
                                                        {{$tip['is_read'] ?? null ? "disabled" : ""}}
                                                        onclick="onDailyTipAllRead()">
                                                    Complete Daily Tip
                                                </button>
                                            </div>

                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-5 col-sm-12 mb-4 d-flex flex-column">
                            <div class="card" style="height: 540px;">
                                <div class="card-body p-3">
                                    <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)">
                                        LIBRARY
                                        OF
                                        RESOURCES & TRAININGS</p>
                                    <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> New Updatesl</p>
                                    <br>
                                    <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - More Conflit Resolution
                                        Strategies</p>
                                    <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Optimizing Your Space
                                        Training
                                        Added</p>
                                    <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Updated Dreivers
                                        Trainings</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="mt-lg-4 mt-2 col-lg-5 col-sm-12 col-md-12 ">
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                            <div class="card" style="height: 530px">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between">
                                        <p class="text-sm fs-12px mt-3 text-white text-bold"
                                           style="color: rgb(160, 174, 192)">
                                            CORE STATS</p>
                                        <p class="text-sm fs-12px mt-3 text-white text-bold"
                                           style="color: rgb(160, 174, 192)">
                                            Interval of Life: (<span class="text-bold text-sm"
                                                                     style="color: #f2661c">{{$user_age}}</span>)</p>
                                    </div>
                                    <p class="text-sm mt-3 fs-12px" style="color: rgb(160, 174, 192)"> Top 3 Traits:</p>
                                    <div class="d-flex flex-column" style="margin-top: -10px">
                                        @if($topThreeStyles)
                                            @foreach($topThreeStyles as $index => $style)
                                                <p class="fw-bold fs-12px"
                                                   style="color: #f2661c; cursor: pointer;margin: unset"
                                                   onclick="goToProfileOverviewPage('{{$style[3]}}','style_{{$index}}')">
                                                    {{ $style[1] }} [{{ $style[0] }}]
                                                </p>
                                            @endforeach
                                        @endif
                                    </div>
                                    <p class="text-sm mt-3 fs-12px" style="color: rgb(160, 174, 192)"> Motivational
                                        Drivers:</p>
                                    <div class="d-flex flex-column" style="margin-top: -10px">
                                        @if($topTwoFeatures)
                                            @foreach($topTwoFeatures as $index => $feature)
                                                <p class="fw-bold fs-12px"
                                                   style="color: #f2661c; cursor: pointer;margin: unset"
                                                   onclick="goToProfileOverviewPage('{{$feature[3]}}','{{'feature_'.$index}}')">
                                                    {{($index%2) === 1 ? 'Co-Pilot: ' : 'Pilot: '}}{{ $feature[1] }} [{{ $feature[0] }}]
                                                </p>
                                            @endforeach
                                        @endif
                                    </div>
                                    <p class="text-sm mt-3 fs-12px" style="color: rgb(160, 174, 192)">Boundaries of Tolerance "Alchemy":</p>
                                    @if($boundary)
                                        <p class="fw-bold fs-12px" style="color: #f2661c; margin-top: -10px; cursor: pointer;"
                                           onclick="goToProfileOverviewPage('{{$boundary['video_url']}}','boundary_dynamic_div')">
                                            @php
                                                $codeParts = explode('-', $boundary['code_number']);
                                                $code = implode('', $codeParts);
                                            @endphp
                                            {{ $boundary['public_name'] ?? '' }} [{{ $code ?? '' }}]
                                        </p>
                                    @endif
                                    <p class="text-sm fs-12px" style="color: rgb(160, 174, 192)"> Communication
                                        Style "Energy Centers":</p>
                                    <div class="d-flex">
                                        @if($topCommunication)
                                            @foreach($topCommunication as $communication)
                                                <p class="fw-bold fs-12px "
                                                   style="color: #f2661c; cursor: pointer;"
                                                   onclick="goToProfileOverviewPage('{{$communication['video_url']}}','communication_{{$index}}')">
                                                    {{ $communication['public_name'] }} @if(!$loop->last)--> &nbsp;@endif
                                                </p>
                                            @endforeach
                                        @endif
                                    </div>
                                    <p class="text-sm fs-12px" style="color: rgb(160, 174, 192)"> Perception of
                                        Life:</p>
                                    @if($preception)
                                        <p class="fw-bold fs-12px" style="color: #f2661c; margin-top: -10px;cursor: pointer;"
                                           onclick="goToProfileOverviewPage('{{$preception['video_url']}}','perception_dynamic_dev')">
                                            {{
                                                ($preception['polarity_code'] == 40 ? "Negatively Charged" :
                                                ($preception['polarity_code'] == 41 ? "Neutrally Charged" :
                                                ($preception['polarity_code'] == 42 ? "Positively Charged" : '')))
                                            }} [{{ $preception['pv'] ?? '' }}]
                                        </p>
                                    @endif
                                    <p class="text-sm fs-12px" style="color: rgb(160, 174, 192)">Energy Pool:</p>
                                    @if($energyPool)
                                        <p class="fw-bold  fs-12px " style="color: #f2661c; margin-top: -10px;cursor: pointer;"
                                           onclick="goToProfileOverviewPage('{{$energyPool['video_url']}}','energy_pool_dynamic_dev')">
                                            {{ $energyPool['code'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 mb-4" style="cursor: pointer;">
                            <div class="card" style="height: 420px;">
                                <div class="card-body p-3">
                                    <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> HELP
                                        I'M
                                        HAVING A CHALLENGE</p>
{{--                                    <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> [CLICK TO ACCESS H. A. I.--}}
{{--                                        SELF-OPTIMIZATION TROUBLESHOOTING INTERFACE]</p>--}}
                                    <h3 class="text-center">Coming Soon</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-lg-4 mt-2 col-lg-4 col-sm-12 col-md-12  features-card ">
                        <div class="col-lg-12  col-md-12 col-sm-12 mb-4 d-flex flex-column">
                            <div class="card" style="height: 410px;">
                                <div class="card-body p-3" style="cursor: pointer">
{{--                                     data-bs-toggle="modal" data-bs-target="#actionPlanModal">--}}
                                    <p class="text-sm fs-12px mt-3 text-white text-bold"
                                       style="color: rgb(160, 174, 192)"> YOUR
                                        OPTIMIZATION STRATEGIES FOR THE
                                        NEXT {{ $userPlanName == 'Core' ? '30' : ($userPlanName == 'Premium' ? '7' : '90') }}
                                        DAYS</p>
                                    @if($plan && !empty($plan['text']))
                                        @if(strlen($plan['text']) > 260)
                                            {!! substr($plan['text'], 0, 265)!!}
                                            &nbsp;&nbsp;
                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                               data-bs-target="#actionPlanModal" style="color: #f2661c;">read
                                                more...
                                            </a>
                                        @else
                                            {!! $plan['text'] !!}
                                        @endif
                                    @else
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12  col-md-12 col-sm-12 mb-4 d-flex flex-column">

                            <div class="card" style="height: 540px;border-radius: 1rem !important;">
                                <div class="card-body p-3">
                                    <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> HIP
                                        -
                                        HumanOp Integration Podcast</p>
                                    <div class="card mb-4"
                                         style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                                        <div class="card-body p-0">
                                            @if($podcast && !empty($podcast->embedded_url))
                                                <div class="row">
                                                    <div class="">
                                                        <div class="numbers mt-3">
                                                            <iframe class="col-lg-12 col-md-12"
                                                                    style="height: 400px; width: 100%;"
                                                                    src="{{$podcast->embedded_url}}"
                                                                    frameborder="0"></iframe>
                                                            <h5 class="font-weight-bolder mb-0">
                                                                <span
                                                                    class="text-success text-sm font-weight-bolder"></span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 text-end">

                                                    </div>
                                                </div>
                                            @else
                                                <p class="text-center text-white">No podcast is uploaded yet</p>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- <div class="row "> -->
                    @if($user->hai_chat == \App\Enums\Admin\Admin::HAI_CHAT_SHOW)
                      @livewire('client.chat.index')
                    @endif
                </div>
            </section>


            <!-- 2nd dislike question Modal -->
            <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalMessage" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                            @livewire('client.client-query.client-query')
                        </div>
                    </div>
                </div>
            </div>

            {{--daily tip modal--}}
            <div class="modal fade" id="dailyTipModal" tabindex="-1" role="dialog" aria-labelledby="dailyTipModal"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label fs-4 text-white">Daily Tip</label>
                                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                                aria-label="Close" id="daily-tip-modal-close-button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <p>{!! $tip['description'] ?? null !!}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group mt-2">
                                        <button style="background-color: #f2661c;" class="btn btn-sm text-white daily-tip-read-button"
                                            {{$tip['is_read'] ?? null ? "disabled" : ""}}
                                            onclick="onDailyTipAllRead()">
                                            Complete Daily Tip
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--answer query modal--}}
            <div class="modal fade" id="answerQueryModal" tabindex="-1" role="dialog" aria-labelledby="answerQueryModal"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label fs-4 text-white">Query Detail</label>
                                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <div class="" style="width: fit-content;">
                                            <div>
                        <span style="color: #f2661c;font-size: 26px;font-weight: 800;display: flex;">
                            Your Query : {{$admin_answer['question']['query'] ?? null}}
                        </span>
                                            </div>
                                            <div>
                                                <div class="text-white mt-2">
                                                    <span> Answer : </span>
                                                    {{ $admin_answer->answer ?? null }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--action plan modal--}}
            <div class="modal fade" id="actionPlanModal" tabindex="-1" role="dialog" aria-labelledby="actionPlanModal"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label fs-4 text-white">
                                            {{ $userPlanName == 'Core' ? '30' : ($userPlanName == 'Premium' ? '7' : '90') }}
                                            Days Action Plan
                                        </label>
                                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <p>{!! $plan['plan_text'] ?? null !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to pause the video and reload the page
        var addPoint = `{{Session::has('add_point') ? '+' . Session::pull('add_point') : '' }}`;

        function pauseVideoAndReload() {
            var video = document.getElementById('podcastVideo');
            video.pause();  // Pause the video
            window.location.reload();  // Reload the page
        }

        // Example: Call the function when the video ends
        var video = document.getElementById('podcastVideo');
        video.onended = function () {
            pauseVideoAndReload();
        };

        // Example: Call the function when a button is clicked
        document.getElementById('someButton').addEventListener('click', pauseVideoAndReload);
    </script>
    <script>
        document.querySelector('.fixed-plugin-button').addEventListener('click', function () {

            var message = document.querySelector('.messageChat').value;

            Livewire.emit('chatMessage', message);
        });

        document.querySelector('.messageChat').addEventListener('keypress', function (e) {
            if (e.key === '\n' && e.ctrlKey) {


                $('.fixed-plugin-button').click();

                var message = document.querySelector('.messageChat').value;

                Livewire.emit('chatMessage', message);

                setTimeout(function () {

                    $('#submitBtn').click()

                    $('#messageChat').val('');
                }, 1000);

            }
        });

        window.Livewire.on('hideModal', function () {

            console.log('hide client query modal');

            setTimeout(function (){
                $('#exampleModalMessage').modal('hide');
                $('#add_feedback').click();
            }, 1000);
        })

        document.querySelector('.haiChatBtn').addEventListener('click', function () {
            document.querySelector('.chatBoxShow').classList.remove('d-block');
            document.querySelector('.chatBoxShow').classList.add('d-none');
            document.querySelector('.chatBoxx').classList.remove('d-none');
            document.querySelector('.chatBoxx').classList.add('d-block');
        });

        function onDailyTipAllRead() {

            $('.daily-tip-read-button').attr('disabled', true);

            $.ajax({
                url: '{{ route("read-daily-tip") }}',
                method: 'POST',
                data: [],
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                },
                success: function (response) {

                    $('#daily-tip-modal-close-button').click();

                    if (response.result.data.point > 0) {
                        animateNumber('+' + response.result.data.point);

                        old_count = $('#coin-count').text();
                        $('#coin-count').text(parseInt(response.result.data.point) + parseInt(old_count));
                    }
                },
                error: function (response) {

                }
            });

        }


    </script>
    <script>
        function scrollToBottom() {
            const chatboxContent = $('#chatbox-content');
            chatboxContent.scrollTop(chatboxContent[0].scrollHeight);
        }

        document.addEventListener('livewire:load', function () {
            const submitBtn = document.getElementById('submitBtn');

            $('#submitBtn').on('click', function () {

                let userMsg = $('#userMessage').val();

                if (userMsg.trim() !== '') {
                    $('#chatbox-new-content').append(`<div style="display: flex; justify-content: flex-end">
                                                                <div class="message user-message">` + userMsg + `</div>
                                                            </div>`);
                }
                $('#userMessage').val('');

                $('#chatbox-new-content').append(`<div id="chatLoader" style="display: flex; justify-content:flex-start">
                                                            <div id="chatDots">
                                                                <span class="chatDot"></span>
                                                                <span class="chatDot"></span>
                                                                <span class="chatDot"></span>
                                                            </div>
                                                        </div>`);

            });
        });
    </script>

    <script>
        window.Livewire.on('showUserAnswerModal', function () {
            // Close any open plugin modal
            $('.fixed-plugin-close-button').click();

            $('.chatBoxClose').click();

            // Trigger the modal to show
            $('#exampleModalMessage').modal('show'); // Assuming you're using Bootstrap's modal

            // Alternatively, if you're not using Bootstrap, you might need to trigger the modal using custom jQuery:
            // $('#chat_ai_question_modal').click();
        });

        function suggestionQueries(text) {

            $('#userMessage').val(text);

            Livewire.emit('chatMessage', text);

            setTimeout(function () {
                $('#submitBtn').click();
            }, 1000)
        }

    </script>

    <script>
        const content = document.getElementById('content');
        const toggleButton = document.querySelector('.chat-ham')
        document.getElementById('toggle-btn').addEventListener('click', function () {


            // toggleButton.className = 'fa-solid fa-bars'


            // Toggle between showing and hiding the content
            if (content.style.display === "none" || content.style.display === "") {
                content.style.display = "flex";
                toggleButton.className = 'fa-solid fa-xmark'

            } else {
                content.style.display = "none";
                toggleButton.className = 'fa-solid fa-bars'

            }
        });

        function goToProfileOverviewPage(src, content_name) {

            window.location.href = "{{url('/client/user-profile-overview') . "?video_url="}}" + src + "&contentName=" + content_name;
        }

        function showDailyTipCompleteText(html_text){

            $('.description-container').css('overflow-y','scroll');

            $('#daily-tip-text').html(html_text);

            if($('#read_all_tip').hasClass('d-none')){

                $('#read_all_tip').removeClass('d-none');

                $('#read_all_tip').addClass('d-flex');
            }
        }
    </script>
@endpush
