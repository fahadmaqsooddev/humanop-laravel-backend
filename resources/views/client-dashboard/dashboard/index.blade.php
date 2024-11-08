@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css" rel="stylesheet">
@endpush
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

    textarea::placeholder {
        color: white !important;
    }

    .text-color-blue {
        color: #1c365e !important;
    }

    /*dashboard page media query*/
    @media screen and (min-width: 300px) and (max-width: 1000px) {

        .card {
            margin: 20px;
        }

        .hai_chat_title_div {
            padding: 20px;
        }

        .haichat_form_div {
            padding: 20px;
        }
    }

    @media screen and (min-width: 768px) and (max-width: 991px) {

        .mainCardFlex {
            display: flex;
        }
    }

    .client-dashboard-stats-heading {
        font-size: 28px;
        font-weight: bold;
    }

    .client-dashboard-long-heading {
        font-size: 20px;
        font-weight: bold;
        text-align: center;
    }

    .description-container > p {
        color: #1c365e !important;
    }

    .traitHeading {
        font-size: 17px;
        font-weight: bold;
    }

    @media screen and (min-width: 550px) and (max-width: 766px) {

        .core-state-card {
            height: auto !important;
        }

    }

    @media screen and (min-width: 766px) and (max-width: 992px) {

        .core-state-card {
            height: 700px !important;
        }

        .help-challenge-card {
            height: 700px !important;
        }
    }

    @media screen and (min-width: 992px) and (max-width: 1200px) {

        .core_stats_heading {
            font-size: 20px;
            margin-bottom: -25px;
        }
    }

    .iconInfo {
        font-size: 16px;
        cursor: pointer;
        color: lightgrey;
        margin-bottom: 5px;
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
                        <div class="col-auto" style="margin-top: 25px">
                            <div class="avatar avatar-xl avatar-icon  ">
                                <img
                                    src="{{ Auth::user()['photo_url']['url'] ?? URL::asset('assets/img/default-user-image.png') }}"
                                    height="80" alt="profile_image" class="w-100 border-radius-lg shadow-sm  ">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="h-100 my-4">
                                <a href="javascript:void(0)">
                                    <h5 class="mb-1 text-white">
                                        {{Auth::user()['first_name']}} {{Auth::user()['last_name']}}
                                    </h5>
                                    @if(!empty(\App\Helpers\Helpers::getWebUser()['optional_trait']))
                                        <p class="mb-0 font-weight-bold text-sm text-white">
                                            Optimal Trait To Be In Right Now:
                                        </p>
                                        <h6 class="text-white"
                                            onclick="goToProfileOverviewPage('{{\App\Helpers\Helpers::getWebUser()['optional_trait'][2]}}','style_{{\App\Helpers\Helpers::getWebUser()['optional_trait'][0]}}')">
                                            <strong>{{ \App\Helpers\Helpers::getWebUser()['optional_trait'][0] }}</strong>
                                        </h6>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($admin_answer && !empty($admin_answer['question']))
            <div class="container-fluid p-2 mt-2 ">

                <div class="d-flex justify-content-between flex-row card card-body text-white gap-5 library-card">
                    <div class="" style="width: fit-content;cursor:pointer" data-bs-toggle="modal"
                         data-bs-target="#answerQueryModal">
                        <div>
                        <span style="font-size: 26px;font-weight: 800;display: flex;" class="text-white">
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
                </div>

            </div>
        @endif

    <!-- main features section -->
        <div class="container-fluid px-0 ">
            <section class=""> {{-- py-3 --}}

                <div class="row">
                    <div class="mt-lg-4 mt-2 col-lg-3 col-sm-12 col-md-12 mainCardFlex">

                        <div class="col-lg-12 col-md-6 col-sm-12 mb-4">
                            <div class="card daily-tip-card" style="height: 530px;">
                                <div class="card-body p-3" data-step="2" style="cursor: pointer;">
                                    <div class="d-flex justify-content-center"
                                         style="border: 2px solid #1c365e;border-radius: 5px">
                                        <h5 class="mb-0 text-center"><strong>Daily Tip</strong> <span
                                                class="iconInfo" data-bs-toggle="modal"
                                                data-bs-target="#dailyTipModel"><i
                                                    class="fa-regular fa-circle-question fa-lg"
                                                    style="color: #0F1535;"></i></span></h5>
                                    </div>
                                    <div class="description-container text-justify" style="height: 335px;">

                                        {{$hide_button = false}}

                                        @if($tip && !empty($tip['description']))
                                            <h6>{{$tip['title']}}</h6>
                                            @if(strlen($tip['description']) > 290)
                                                <?php
                                                  $hide_button = true;
                                                ?>
                                                <span id="daily-tip-text">
            {!! substr($tip['description'], 0, 305) !!}
            <a href="javascript:void(0)"
               onclick="showDailyTipCompleteText(`{{$tip['description']}}`)"
               style="color: #f2661c;">read more...</a>
        </span>
                                            @else
                                                {!! $tip['description'] !!}
                                            @endif
                                        @else
                                            <p>Click here to:
                                                <a href="{{ url('client/intro-assessment') }}" target="_self"
                                                   style="color: orange;">Take the Assessment</a>
                                            </p>
                                        @endif
                                        @if($tip && $assessment)
                                            <div>

                                                <div
                                                    class="{{$hide_button ? "d-none" : "d-none"}} justify-content-center mt-2"
                                                    id="read_all_tip">
                                                    <button
                                                        class="rainbow-border-user-nav-btn btn-sm daily-tip-read-button"
{{--                                                        {{$tip['is_read'] ?? null ? "disabled" : ""}}--}}
data-bs-toggle="modal" data-bs-target="#daily-tip-completed"
                                                        onclick="onDailyTipAllRead()">
                                                        Complete Daily Tip
                                                    </button>
                                                </div>

                                            </div>
                                        @endif
                                    </div>

                                    @if($tip && $assessment)
                                        <div>

                                            <div
                                                class="{{$hide_button ? "d-none" : "d-flex"}} justify-content-center mt-2">
                                                <button style="background-color: #f2661c;"
                                                        class="rainbow-border-user-nav-btn btn-sm daily-tip-read-button"
                                                        data-bs-toggle="modal" data-bs-target="#daily-tip-completed"
                                                        onclick="onDailyTipAllRead()">
                                                    Complete Daily Tip
                                                </button>
                                            </div>

                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6 col-sm-12 mb-4">
                            <div class="card library-card p-3" style="height: 530px!important;" data-step="6">
                                <div class="d-flex justify-content-center"
                                     style="border: 2px solid white;border-radius: 5px">
                                    <h5 class="text-white mb-0 text-center"
                                        style="color: rgb(160, 174, 192)"><strong>LIBRARY
                                            OF
                                            RESOURCES & TRAININGS </strong><span class="iconInfo" data-bs-toggle="modal"
                                                                                 data-bs-target="#libraryResourceModel"><i
                                                class="fa-regular fa-circle-question fa-lg"
                                                style="color: white;"></i></span></h5>
                                </div>

                                <a href="{{route('resource')}}">
                                    <p style="font-size: 15px;cursor:pointer" class="my-1 text-white"><strong> Click
                                            here to access Resources & Trainings for:</strong></p>
                                </a>
                                <ul>
                                    <li style="font-size: 14px">Deeper understanding of the science behind the patented
                                        technology
                                    </li>
                                    <li style="font-size: 14px"> How to use the results of this technology to optimize
                                        your life and work
                                    </li>
                                    <li style="font-size: 14px"> Deeper dives into understanding each element of your
                                        authentic self
                                    </li>
                                    <li style="font-size: 14px"> How to strategies to help resolve challenges you’re
                                        experiencing in all areas of your life.
                                    </li>
                                </ul>


                            </div>

                        </div>

                    </div>

                    <div class="mt-lg-4 mt-2 col-lg-5 col-sm-12 col-md-12 mainCardFlex">

                        <div class="col-lg-12 col-md-6 col-sm-12 mb-4">
                            <div class="card core-state-card" style="height: 530px;" data-step="3">
                                <div class="card-body p-3">
                                    <div>
                                        <div class="d-flex justify-content-center"
                                             style="border: 2px solid #1c365e;border-radius: 5px">
                                            <h5 class="text-color-blue mb-0 text-center">
                                                <strong> CORE STATS </strong><span style="color: white!important;"
                                                                                   class="iconInfo"
                                                                                   data-bs-toggle="modal"
                                                                                   data-bs-target="#coreStatsModel"><i
                                                        class="fa-regular fa-circle-question fa-lg"
                                                        style="color: #0F1535;"></i></span>
                                            </h5>
                                        </div>

                                        @if(16 <= $age && $age <= 20)

                                            <p class="text-sm fs-12px text-color-blue text-bold"
                                               onclick="goToProfileOverviewPage('{{asset('assets/video/Cycle of Life - Motivation 16-20.mp4')}}','motivation_life_cycle')"
                                               style="color: rgb(160, 174, 192); cursor: pointer;">
                                                Interval of Life: (<span class="text-bold text-sm"
                                                                         style="color: #f2661c">{{$user_age['interval']}}</span>)
                                            </p>

                                        @elseif(21 <= $age && $age <= 29)

                                            <p class="text-sm fs-12px  text-color-blue text-bold"
                                               onclick="goToProfileOverviewPage('{{asset('assets/video/Cycle of Life - Roadworthy 21-29.mp4')}}','roadworthy_life_cycle')"
                                               style="color: rgb(160, 174, 192); cursor: pointer;">
                                                Interval of Life: (<span class="text-bold text-sm"
                                                                         style="color: #f2661c">{{$user_age['interval']}}</span>)
                                            </p>

                                        @elseif(30 <= $age && $age <= 33)

                                            <p class="text-sm fs-12px  text-color-blue text-bold"
                                               onclick="goToProfileOverviewPage('{{asset('assets/video/The Cycle of Life - Power Interval 30-33.mp4')}}','power_life_cycle')"
                                               style="color: rgb(160, 174, 192); cursor: pointer;">
                                                Interval of Life: (<span class="text-bold text-sm"
                                                                         style="color: #f2661c">{{$user_age['interval']}}</span>)
                                            </p>

                                        @elseif(34 <= $age && $age <= 42)

                                            <p class="text-sm fs-12px  text-color-blue text-bold"
                                               onclick="goToProfileOverviewPage('{{asset('assets/video/The Cycle of Life - Mid-Life Transformation 34-43.mp4')}}','mid_life_life_cycle')"
                                               style="color: rgb(160, 174, 192); cursor: pointer;">
                                                Interval of Life: (<span class="text-bold text-sm"
                                                                         style="color: #f2661c">{{$user_age['interval']}}</span>)
                                            </p>

                                        @elseif(43 <= $age && $age <= 52)

                                            <p class="text-sm fs-12px text-color-blue text-bold"
                                               onclick="goToProfileOverviewPage('{{asset('assets/video/Cycle of Life - Awareness Interval 43-52.mp4')}}','awareness_life_cycle')"
                                               style="color: rgb(160, 174, 192); cursor: pointer;">
                                                Interval of Life: (<span class="text-bold text-sm"
                                                                         style="color: #f2661c">{{$user_age['interval']}}</span>)
                                            </p>

                                        @elseif(52 <= $age && $age <= 66)

                                            <p class="text-sm fs-12px  text-color-blue text-bold"
                                               onclick="goToProfileOverviewPage('{{asset('assets/video/Cycle of Life - Pay It Forward 52-66.mp4')}}','forward_life_cycle')"
                                               style="color: rgb(160, 174, 192); cursor: pointer;">
                                                Interval of Life: (<span class="text-bold text-sm"
                                                                         style="color: #f2661c">{{$user_age['interval']}}</span>)
                                            </p>


                                        @elseif(66 <= $age && $age <= 70)

                                            <p class="text-sm fs-12px  text-color-blue text-bold"
                                               onclick="goToProfileOverviewPage('{{asset('assets/video/Cycle of Life - Liberated 66-70.mp4')}}','liberated_life_cycle')"
                                               style="color: rgb(160, 174, 192); cursor: pointer;">
                                                Interval of Life: (<span class="text-bold text-sm"
                                                                         style="color: #f2661c">{{$user_age['interval']}}</span>)
                                            </p>

                                        @elseif(70 <= $age && $age <= 75)

                                            <p class="text-sm fs-12px  text-color-blue text-bold"
                                               onclick="goToProfileOverviewPage('{{asset('assets/video/The Cycle of Life - Being 70-75.mp4')}}','being_life_cycle')"
                                               style="color: rgb(160, 174, 192); cursor: pointer;">
                                                Interval of Life: (<span class="text-bold text-sm"
                                                                         style="color: #f2661c">{{$user_age['interval']}}</span>)
                                            </p>

                                        @else

                                            <p class="text-sm fs-12px  text-color-blue text-bold"
                                               onclick="goToProfileOverviewPage('{{asset('assets/video/The Cycle of Life - Life Review Interval Ages 75-84.mp4')}}','review_life_cycle')"
                                               style="color: rgb(160, 174, 192); cursor: pointer;">
                                                Interval of Life: (<span class="text-bold text-sm"
                                                                         style="color: #f2661c">{{$user_age['interval']}}</span>)
                                            </p>

                                        @endif


                                    </div>
                                    <p class="text-color-blue traitHeading"> Top 3 Traits:</p>
                                    <div class="d-flex flex-column" style="margin-top: -10px">
                                        @if($topThreeStyles)
                                            @foreach(array_slice($topThreeStyles, 0, 3) as $index => $style)
                                                <p class="fw-bold fs-12px text-color-blue"
                                                   style=" cursor: pointer;margin: unset"
                                                   onclick="goToProfileOverviewPage('{{$style[3]}}','style_{{$style[1]}}')">
                                                    {{ $style[1] }} [{{ $style[0] }}]
                                                </p>
                                            @endforeach
                                        @endif
                                    </div>
                                    <p class="text-color-blue traitHeading"> Motivational
                                        Drivers:</p>
                                    <div class="d-flex flex-column text-color-blue" style="margin-top: -10px">
                                        @if($topTwoFeatures)
                                            @foreach($topTwoFeatures as $index => $feature)
                                                <p class="fw-bold fs-12px "
                                                   style=" cursor: pointer;margin: unset"
                                                   onclick="goToProfileOverviewPage('{{$feature[3]}}','{{'feature_'.$feature[1]}}')">
                                                    {{($index%2) === 1 ? 'Co-Pilot: ' : 'Pilot: '}}{{ $feature[1] }}
                                                    [{{ $feature[0] }}]
                                                </p>
                                            @endforeach
                                        @endif
                                    </div>
                                    <p class="text-color-blue traitHeading">Boundaries of
                                        Tolerance "Alchemy":</p>
                                    @if($boundary)
                                        <p class="fw-bold fs-12px text-color-blue"
                                           style="margin-top: -10px; cursor: pointer;"
                                           onclick="goToProfileOverviewPage('{{$boundary['video_url']}}','boundary_dynamic_div')">
                                            @php
                                                $codeParts = explode('-', $boundary['code_number']);
                                                $code = implode('', $codeParts);
                                            @endphp
                                            {{ $boundary['public_name'] ?? '' }} [{{ $code ?? '' }}]
                                        </p>
                                    @endif
                                    <p class="text-color-blue traitHeading"> Communication
                                        Style "Energy Centers":</p>
                                    <div class="d-flex text-color-blue flex-wrap" style="margin-top: -15px">
                                        @if($topCommunication)
                                            @foreach($topCommunication as $communication)
                                                <p class="fw-bold fs-12px"
                                                   style=" cursor: pointer;"
                                                   onclick="goToProfileOverviewPage('{{$communication['video_url']}}','communication_{{$index}}')">
                                                    {{--                                                    {{dd($communication)}}--}}
                                                    <span>{{ $communication['public_name'] . ' [' .($assessment[$communication['code_key']] ?? null) . ']' }} @if(!$loop->last) &rarr;</span>
                                                    &nbsp;@endif
                                                </p>
                                            @endforeach
                                        @endif
                                    </div>
                                    <p class="text-color-blue traitHeading"
                                       style="color: rgb(160, 174, 192); margin-top: -13px">
                                        Perception of
                                        Life:</p>
                                    @if($preception)
                                        <p class="fw-bold fs-12px text-color-blue"
                                           style=" margin-top: -10px;cursor: pointer;"
                                           onclick="goToProfileOverviewPage('{{$preception['video_url']}}','perception_dynamic_dev')">
                                            {{
                                                ($preception['polarity_code'] == 40 ? "Negatively Charged" :
                                                ($preception['polarity_code'] == 41 ? "Neutrally Charged" :
                                                ($preception['polarity_code'] == 42 ? "Positively Charged" : '')))
                                            }} [{{ $preception['pv'] ?? '' }}]
                                        </p>
                                    @endif
                                    <p class="text-color-blue traitHeading" style="color: rgb(160, 174, 192)">Energy
                                        Pool:</p>
                                    @if($energyPool)
                                        <p class="fw-bold  fs-12px text-color-blue "
                                           style="margin-top: -10px;cursor: pointer;"
                                           onclick="goToProfileOverviewPage('{{$energyPool['video_url']}}','energy_pool_dynamic_dev')">
                                            {{ $energyPool['code'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6 col-sm-12 mb-4"
                             style="cursor: pointer;">
                            <div class="card help-challenge-card p-3" style="height: 530px;" data-step="8">
                                <div class="d-flex justify-content-center"
                                     style="border: 2px solid white;border-radius: 5px">
                                    <h5 class="text-white mb-0 text-center"
                                        style="color: rgb(160, 174, 192)"><strong>HELP
                                            I'M
                                            HAVING A CHALLENGE </strong><span class="iconInfo" data-bs-toggle="modal"
                                                                              data-bs-target="#helpChallangeModel" ><i
                                                class="fa-regular fa-circle-question fa-lg"
                                                style="color: white;"></i></span></h5>
                                </div>
                                <div class="card-body p-3 d-flex justify-content-center align-items-center" >
                                    <div >
                                        <button  class="rainbow-border-user-nav-btn btn-lg " id="open-chat-btn" style="">Get Help!</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="mt-lg-4 mt-2 col-lg-4 col-sm-12 col-md-12 mainCardFlex">

                        <div class="col-lg-12 col-md-6 col-sm-12 mb-4">
                            <div class="card optimization-strategy-card p-3" style="height: 530px!important;"
                                 data-step="5">
                                <div class="d-flex justify-content-center"
                                     style="border: 2px solid white;border-radius: 5px">
                                    <h5 class="text-white mb-0 text-center">
                                        <strong>
                                            Your 90-Day Optimization Plan </strong><span class="iconInfo"
                                                                                         data-bs-toggle="modal"
                                                                                         data-bs-target="#actionPlanModel"><i
                                                class="fa-regular fa-circle-question fa-lg"
                                                style="color: white;"></i></span>
                                    </h5>
                                </div>
                                <div class="card-body p-3 text-white "
                                     style="cursor: pointer; overflow: auto">
                                    {{--                                    data-bs-toggle="modal" data-bs-target="#actionPlanModal">--}}
                                    <div>
                                        @if($actionPlan)
{{--                                            @php--}}
{{--                                                $html = $actionPlan['plan_text'] ?? null;--}}

{{--                                                if ($html) {--}}
{{--                                                    // Create a new DOMDocument instance--}}
{{--                                                    $dom = new DOMDocument();--}}

{{--                                                    // Suppress warnings due to invalid HTML and load the HTML--}}
{{--                                                    libxml_use_internal_errors(true);--}}
{{--                                                    $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));--}}
{{--                                                    libxml_clear_errors();--}}

{{--                                                    // Initialize variables to hold the first two tags--}}
{{--                                                    $firstTag = null;--}}
{{--                                                    $secondTag = null;--}}
{{--                                                    $tagsFound = 0;--}}

{{--                                                    // Iterate through all child nodes of the body to find the first two tags--}}
{{--                                                    foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {--}}
{{--                                                        if ($node->nodeType === XML_ELEMENT_NODE) { // Check if it is an element node (tag)--}}
{{--                                                            if (!$firstTag) {--}}
{{--                                                                $firstTag = $dom->saveHTML($node);--}}
{{--                                                                $tagsFound++;--}}
{{--                                                            } elseif (!$secondTag) {--}}
{{--                                                                $secondTag = $dom->saveHTML($node);--}}
{{--                                                                $tagsFound++;--}}
{{--                                                            }--}}

{{--                                                            // Stop after capturing two tags--}}
{{--                                                            if ($tagsFound === 2) {--}}
{{--                                                                break;--}}
{{--                                                            }--}}
{{--                                                        }--}}
{{--                                                    }--}}
{{--                                                }--}}
{{--                                            @endphp--}}

                                        {!! $actionPlan['plan_text'] !!}
{{--                                            <span data-bs-toggle="modal" data-bs-target="#nintyDaysActionPlan"--}}
{{--                                                  style="color: #f2661c; cursor: pointer">view more...</span>--}}
                                        @else
                                            <p>Click here to:
                                                <a href="{{ url('client/intro-assessment') }}" target="_self"
                                                   style="color: orange;">Take the Assessment</a>
                                            </p>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6 col-sm-12 mb-4">

                            <div class="card podcast-card" style="height: 530px!important;" data-step="7">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-center"
                                         style="border: 2px solid #1c365e;border-radius: 5px">
                                        <h5 class="custom-text-dark mb-0 text-center"><strong> HIP
                                                -
                                                HumanOp Integration Podcast</strong></h5>
                                    </div>
                                    <div class="card mb-4"
                                    >
                                        <div class="card-body p-0">
                                            @if($podcast && !empty($podcast->embedded_url))
                                                <div class="row">
                                                    <div class="podcast-card">
                                                        <div class="numbers">
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
                    <div id="chat-component-main-container">
                    <div id="chat-component-container">
                    @if($user->hai_chat == \App\Enums\Admin\Admin::HAI_CHAT_SHOW)
                        @livewire('client.chat.index')
                    @endif
                    </div>
                    </div>
                </div>
            </section>


            <!-- 2nd dislike question Modal -->
            <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalMessage" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body" style="border-radius: 9px">
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
                        <div class="modal-body" style="border-radius: 9px">
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
                                        <button style="background-color: #f2661c;"
                                                class="btn btn-sm text-white daily-tip-read-button"
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
                        <div class="modal-body" style="border-radius: 9px">
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
                        <div class="modal-body" style=" border-radius: 9px">
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

            <!-- Modal -->
            <div class="modal fade" id="humanOpWalletModal" tabindex="-1" role="dialog"
                 aria-labelledby="humanOpWalletModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content py-3">

                        <div class="p-2">
                            <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <div class="coins d-flex justify-content-center">
                                <img src="{{ asset('assets/img/coins.gif') }}" alt="Coins falling"
                                     style="width: 100px; height: 150px; margin-top: -80px;">
                            </div>
                            <div class="d-flex justify-content-center">
                                <!-- Points Counter Circle -->
                                <div class="fw-bold display-5 d-flex align-items-center justify-content-center"
                                     id="coin-count"
                                     style="border-radius: 50%; height: 50px; width: 50px; font-size: 16px; border: 1px solid white; color: white; text-shadow: 0 0 5px #f2661c, 0 0 10px #f2661c; background-color: #f2661c; margin-right: -5px;;z-index:4">
                                    <span>{{ Auth::user()['point'] }}</span>
                                </div>
                                <!-- Coins Label - extending from the circle -->
                                <div class="fw-bold display-5 d-flex align-items-center justify-content-center"
                                     id="coin-label"
                                     style="border-radius: 0px 40% 40% 0px; height: 40px;z-index:2; width: 70px; font-size: 16px; border: 1px solid #f2661c; color: #f2661c; background-color: white; margin-left: -4px;margin-top: 5px">
                                    <span style="color: #f2661c;">HP</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Tip Info Model -->
        <div class="modal fade" id="dailyTipModel" tabindex="-1"
             role="dialog"
             aria-labelledby="dailyTipModel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <label class="form-label fs-4 text-white">{{$dailyTipInfo['name']}}</label>
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-info-modal-button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <p class="text-white mt-4" style="text-align: justify">{{$dailyTipInfo['information']}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 90 days Plan Info Model -->
        <div class="modal fade" id="actionPlanModel" tabindex="-1"
             role="dialog"
             aria-labelledby="dailyTipModel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <label class="form-label fs-4 text-white">{{$actionPlanInfo['name']}}</label>
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-info-modal-button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <p class="text-white mt-4"
                               style="text-align: justify">{{$actionPlanInfo['information']}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Core Stats Info Model -->
        <div class="modal fade" id="coreStatsModel" tabindex="-1"
             role="dialog"
             aria-labelledby="dailyTipModel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <label class="form-label fs-4 text-white">{{$coreStatsInfo['name']}}</label>
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-info-modal-button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <p class="text-white mt-4" style="text-align: justify">{{$coreStatsInfo['information']}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Library Resource Info Model -->
        <div class="modal fade" id="libraryResourceModel" tabindex="-1"
             role="dialog"
             aria-labelledby="dailyTipModel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <label class="form-label fs-4 text-white">{{$libraryResourceInfo['name']}}</label>
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-info-modal-button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <p class="text-white mt-4"
                               style="text-align: justify">{{$libraryResourceInfo['information']}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Help I'm having Challenging Info Model -->
        <div class="modal fade" id="helpChallangeModel" tabindex="-1"
             role="dialog"
             aria-labelledby="dailyTipModel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <label class="form-label fs-4 text-white">{{$helpInfo['name']}}</label>
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-info-modal-button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <p class="text-white mt-4" style="text-align: justify">{{$helpInfo['information']}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--    intro pop up --}}

        <div class="modal fade" id="introModel" tabindex="-1"
             role="dialog"
             aria-labelledby="couponModel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <div class="form-label fs-4 text-white text-center">Welcome to the HumanOp Assessment!
                            </div>
                            <br>
                            <div class="card-body pt-0 text-white">
                                <p>
                                    You’re about to begin a life-changing journey. The HumanOp Assessment is designed to unveil your true nature not based on opinions, but guided by the unchanging physical laws of nature and cutting-edge science.
                                </p>
                                <p class="text-white">
                                    Ready to discover what makes you truly unique and learn the actionable steps to optimize your life? Click the link to get started!
                                </p>
                                <div class="d-flex justify-content-between">
                                    <button  data-bs-dismiss="modal" aria-label="Close" class="start-tour btn-sm mt-2 mb-0 rainbow-border-user-nav-btn" style="background-color: #f2661c; color: white; font-size: 14px">
                                        Start Tutorial
                                    </button>
                                    <button data-bs-dismiss="modal"
                                            aria-label="Close" class="btn-sm float-end mt-2 mb-0 rainbow-border-user-nav-btn" style="background-color: #f2661c; color: white; font-size: 14px">
                                        Skip Tutorial
                                    </button>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
{{--        having challenge modal--}}
        @if($user->hai_chat == \App\Enums\Admin\Admin::HAI_CHAT_SHOW)
        <div class="modal fade" id="having-challenge-modal" tabindex="-1"
             role="dialog" style="background-color: rgb(0 0 0 / 88%)"
             aria-labelledby="having-challenge-modal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <label class="form-label fs-4 text-white">Having Challenge Let Me Help You!</label>
                            <button type="button" class="close modal-close-btn" id="close-challenge-modal" data-bs-dismiss="modal"
                                    aria-label="Close" >
                                <span aria-hidden="true">&times;</span>
                            </button>


                            <div id="having-challenge-chat"  >

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
         @endif
        {{--daily tip already completed--}}
        <div class="modal fade" id="daily-tip-completed" tabindex="-1"
             role="dialog"
             aria-labelledby="dailyTipCompleted" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <label class="form-label fs-4 text-white">Daily Tip Completed! 🌟</label>
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-info-modal-button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <p class="text-white mt-4" style="text-align: justify"> Well done! You’ve completed today’s
                                tip. Keep this momentum going and check back tomorrow for another tip!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--    end daily tip complete here--}}
        <button class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#introModel"
                id="open-intro-modal">
        </button>
        @endsection

        @push('javascript')
            <script>

                const descriptionContainer = document.querySelector('.description-container');
                descriptionContainer.addEventListener('wheel', (event) => {
                    event.preventDefault();

                    descriptionContainer.scrollBy({
                        top: event.deltaY < 0 ? -30 : 30,
                    });
                });

            </script>
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

                    setTimeout(function () {
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

                function introCompleted() {
                    $.ajax({
                        url: '{{ route("complete_intro") }}',
                        method: 'POST',
                        data: [],
                        headers: {
                            'X-CSRF-TOKEN': "{{csrf_token()}}"
                        },
                        success: function (response) {

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

                function showDailyTipCompleteText(html_text) {

                    $('.description-container').css('overflow-y', 'scroll');

                    $('#daily-tip-text').html(html_text);

                    if ($('#read_all_tip').hasClass('d-none')) {

                        $('#read_all_tip').removeClass('d-none');

                        $('#read_all_tip').addClass('d-flex');
                    }

                }
            </script>
        @endpush

        @push('javascript')
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
            <script>

                // Set up the button click event to start the tour
                $('.start-tour').on('click', function () {
                    introJs().setOptions({
                        exitOnOverlayClick: false,
                        tooltipPosition: 'bottom',
                        scrollToElement: true,
                        doneLabel: 'Exit Tutorial',
                        steps: [
                            {
                                element: document.querySelector('[data-step="1"]'),
                                title: 'HumanOp Assessment',
                                intro: 'Here is where you can take your very first HumanOp Assessment. Taking your first assessment will not only unlock all the features on the platform but it is also the first step on your path to understanding your true nature and optimizing your life.',
                            },
                            {
                                element: document.querySelector('[data-step="2"]'),
                                title: 'Daily Tips',
                                intro: 'Here is where you will see your daily tips. After you take your HumanOp Assessment, HAi will generate daily tips to help you optimize your day - every day!',
                            },
                            {
                                element: document.querySelector('[data-step="3"]'),
                                title: 'Core Stats',
                                intro: 'Here is where your Core Stats are. These stats are a quick summary of your latest HumanOp Assessment to help remind you of your results.  Clicking on each stat will take you to the Full Results page so you can watch the explanations of each component.',

                            },
                            {
                                element: document.querySelector('[data-step="4"]'),
                                title: 'Optimal Trait To Be In Right Now',
                                intro: 'Check here in the morning, mid-day, and in the evening to reference the most optimal trait to align with at these various times a day.',

                            },
                            {
                                element: document.querySelector('[data-step="5"]'),
                                title: '90-Day Optimization Plan',
                                intro: 'Here is where you access your 90-Day Optimization Plan.  This plan references your latest assessment results and offers you specific optimization strategies related to your results that you can implement over the next 90-days.',

                            },
                            {
                                element: document.querySelector('[data-step="6"]'),
                                title: 'Library of Resources & Trainings',
                                intro: 'Here is where you’ll find an ever growing library of resources and trainings that will deepen the understanding of your results and support you on your self-optimization journey.',

                            },
                            {
                                element: document.querySelector('[data-step="7"]'),
                                title: 'HumanOp Integration Podcast',
                                intro: 'Here is where you access the HumanOp Integration Podcast - listen to the latest episodes and learn about how to optimally integrate and make the most of your HumanOp experience.',

                            },
                            {
                                element: document.querySelector('[data-step="8"]'),
                                title: 'Help I’m Having A Challenge',
                                intro: 'This is where you can go to interact with HAi® to share any challenge you may be having and where you will receive supportive feedback.',

                            },
                            {
                                element: document.querySelector('[data-step="9"]'),
                                title: 'Ask HAi Questions',
                                intro: `<div>Here is where you can ask questions or have conversation with HAi, our proprietary chat powered by HumanOp Authentic Intelligence® for support, guidance, and strategies around how best to use the HumanOp HAi Optimization System (HAi OS) to optimize every aspect of your life.Congratulations on finishing your first tutorial.Now let’s have you take your first assessment!Make sure you give yourself 10-15 minutes of no distractions to focus on you so you can make the most out of this powerful technology.
                       <div class="d-flex justify-content-center">
              <a href="{{ url('client/intro-assessment') }}" class="btn-sm mt-2 mb-1 rainbow-border-user-nav-btn" style="background-color: #f2661c; color: white; font-size: 14px">

                   Take Assessment
                        </a>
                                </div>
                         </div>`,
                            }
                        ]
                    }).onbeforechange(function (targetElement) {
                        // Custom scroll behavior for deeply nested elements
                        targetElement.scrollIntoView();
                    }).start();
                });
                window.onload = function () {
                    $(document).ready(function () {
                        if ({{\App\Helpers\Helpers::getWebUser()['intro_check']}} == 2) {
                            $('#open-intro-modal').trigger('click');
                            introCompleted();
                        }
                    });
                };
            </script>

            <script>

                var addPoint = `{{Session::has('add_point') ? '+' . Session::pull('add_point') : '' }}`;


                function animateNumber(addPoint) {
                    const navContainer = document.querySelector(".abc");
                    const animationEffect = document.createElement('span');

                    animationEffect.classList.add('animated-number');
                    animationEffect.textContent = addPoint;
                    animationEffect.style.color = 'orange';
                    animationEffect.style.fontWeight = '900';
                    animationEffect.style.fontSize = '2rem';
                    animationEffect.style.textShadow = '0 0 5px orange, 0 0 10px orange';
                    navContainer.appendChild(animationEffect);

                    // Add a slight delay before starting the animation
                    setTimeout(() => {
                        animationEffect.classList.add('fade-in');
                    }, 100); // Slightly longer delay to allow the element to render

                    setTimeout(() => {
                        animationEffect.classList.add('disappear');
                    }, 8000);

                    setTimeout(() => {
                        animationEffect.remove();
                    }, 9000);
                }

                animateNumber(addPoint);

            </script>

            <script>
                $(document).ready(function () {
                    const $chatContainer = $('#chat-component-container'); // Original location
                    const $chatModalContainer = $('#having-challenge-chat'); // Modal container

                    // Show modal and move component
                    $('#open-chat-btn').on('click', function () {
                        $chatContainer.appendTo($chatModalContainer); // Move to modal
                        $('.chat-heading').css('color','white');
                        $('.chat-heading').css('margin-top','10px');
                        $('.chat-question-mark').css('display','none');
                        $('#having-challenge-modal').modal('show'); // Show the modal
                    });

                    // Log and move component back when modal is hidden
                    $('#close-challenge-modal').on('click', function () {
                        $chatContainer.appendTo($('#chat-component-main-container')); // Move back to original location
                        $('.chat-heading').css('color','black');
                        $('.chat-heading').css('margin-top','0px');
                        $('.chat-question-mark').css('display','inline-block');
                    });
                       $('#having-challenge-modal').on('hidden.bs.modal', function () {
                           $chatContainer.appendTo($('#chat-component-main-container'));
                           $('.chat-heading').css('color','black');
                           $('.chat-heading').css('margin-top','0px');
                           $('.chat-question-mark').css('display','inline-block');
                       });
                    });


            </script>
    @endpush
