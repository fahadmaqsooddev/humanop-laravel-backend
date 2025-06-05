@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
@push('css')
{{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">--}}
    <style>
        .slider-padding {
            padding: 10px 80px 10px 80px;
            text-align: justify;
            color: white;
        }
        video.slider-padding{
            width:100% !important;
            height:auto !important;
        }
        p.slider-padding{
            padding-bottom:0px;

        }
        h2.slider-padding{
    text.align:center;
        }
        ul.slider-padding{
            text-align: start;

        }

        @media (max-width: 575px) {
        .slider-padding{
            padding: 10px 0;

        }
        img.carousel-image{
            min-width: 200px;
            height:70px;
        }
        p.slider-padding{
            padding:0;

        }
        img.ult-img{
            min-width: 200px;
            height:70px;
        }
        }
        @media (min-width: 576px) and (max-width: 767px) {
            .slider-padding {
            padding: 10px 50px;
            }
        }


        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        .container, .video-controls, .video-timer, .options {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            user-select: none;
            overflow: hidden;
            max-width: 1200px;
            border-radius: 5px;
            /*background: #000;*/
            aspect-ratio: 16 / 9;
            position: relative;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .container.fullscreen {
            max-width: 100%;
            width: 100%;
            height: 100vh;
            border-radius: 0px;
        }

        .wrapper {
            position: absolute;
            left: 0;
            right: 0;
            z-index: 1;
            opacity: 0;
            bottom: -15px;
            transition: all 0.08s ease;
        }

        .container.show-controls .wrapper {
            opacity: 1;
            bottom: 0;
            transition: all 0.13s ease;
        }

        .wrapper::before {
            content: "";
            bottom: 0;
            width: 100%;
            z-index: -1;
            position: absolute;
            height: calc(100% + 35px);
            pointer-events: none;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        }

        .video-timeline {
            height: 7px;
            width: 100%;
            cursor: pointer;
        }

        .video-timeline .progress-area {
            height: 3px;
            position: relative;
            background: rgba(255, 255, 255, 0.6);
        }

        .progress-area span {
            position: absolute;
            left: 50%;
            top: -25px;
            font-size: 13px;
            color: #fff;
            pointer-events: none;
            transform: translateX(-50%);
        }

        .progress-area .progress-bar {
            width: 0%;
            height: 100%;
            position: relative;
            background: #1b3a62;
        }

        .progress-bar::before {
            content: "";
            right: 0;
            top: 50%;
            height: 13px;
            width: 13px;
            position: absolute;
            border-radius: 50%;
            background: #1b3a62;
            transform: translateY(-50%);
        }

        .progress-bar::before, .progress-area span {
            display: none;
        }

        .video-timeline:hover .progress-bar::before,
        .video-timeline:hover .progress-area span {
            display: block;
        }

        .wrapper .video-controls {
            padding: 5px 20px 10px;
        }

        .video-controls .options {
            width: 100%;
        }

        .video-controls .options:first-child {
            justify-content: flex-start;
        }

        .video-controls .options:last-child {
            justify-content: flex-end;
        }

        .options button {
            height: 40px;
            width: 40px;
            font-size: 19px;
            border: none;
            cursor: pointer;
            background: none;
            color: #efefef;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .options button :where(i, span) {
            height: 100%;
            width: 100%;
            line-height: 40px;
        }

        .options button:hover :where(i, span) {
            color: #fff;
        }

        .options button:active :where(i, span) {
            transform: scale(0.9);
        }

        .options button span {
            font-size: 23px;
        }

        .options input {
            height: 4px;
            margin-left: 3px;
            max-width: 75px;
            accent-color: #1b3a62;
        }

        .options .video-timer {
            color: #efefef;
            margin-left: 15px;
            font-size: 14px;
        }

        .video-timer .separator {
            margin: 0 5px;
            font-size: 16px;
            font-family: "Open sans";
        }

        /*.playback-content {*/
        /*    display: flex;*/
        /*    position: relative;*/
        /*}*/

        .playback-content .speed-options {
            position: absolute;
            list-style: none;
            left: -40px;
            bottom: 40px;
            width: 95px;
            overflow: hidden;
            opacity: 0;
            border-radius: 4px;
            pointer-events: none;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: opacity 0.13s ease;
        }

        /*.playback-content .speed-options.show {*/
        /*    opacity: 1;*/
        /*    pointer-events: auto;*/
        /*}*/
        .container video {
            width: 120%;
        }

        @media screen and (max-width: 540px) {

            .wrapper .video-controls {
                padding: 3px 10px 7px;
            }

            .options input, .progress-area span {
                display: none !important;
            }

            .options button {
                height: 30px;
                width: 30px;
                font-size: 17px;
            }

            .options .video-timer {
                margin-left: 5px;
            }

            .video-timer .separator {
                font-size: 14px;
                margin: 0 2px;
            }

            .options button :where(i, span) {
                line-height: 30px;
            }

            .options button span {
                font-size: 21px;
            }

            .options .video-timer, .progress-area span, .speed-options li {
                font-size: 12px;
            }

            /*.playback-content .speed-options {*/
            /*    width: 75px;*/
            /*    left: -30px;*/
            /*    bottom: 30px;*/
            /*}*/

            /*.speed-options li{*/
            /*    margin: 1px 0;*/
            /*    padding: 3px 0 3px 10px;*/
            /*}*/
            .right .pic-in-pic {
                display: none;
            }
        }


    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12 position-relative z-index-2">
            <div class="container-fluid px-0 px-md-5">
                <section>
                    <div class="row mt-lg-4 mt-2">
                        <div class="col-12">
                            <div class="card px-0" style="text-align: center">
                                <div>
                                    <a href="{{url('client/generate-pdf/'. $id)}}" target="_blank"
                                       class="btn btn-sm float-end mt-4 mb-4 text-white mx-4"
                                       style="background-color: #1b3a62">PDF</a>
                                </div>
                                <div class="card-body p-3 ">
                                    <div style="border: 0px solid #ccc;"><img src="{{asset('assets/img/ultlogo.png')}}"
                                                                              style="background:#351a0d; padding: 0px;border-radius: 5px" class="w-70 ult-img"/>
                                    </div>
                                    <div class="text-white mt-2 fs-10px">“Advanced Human Assessment Technology for a Better
                                        Mankind”
                                    </div>
                                    <h1 class="text-white">ULT Summary Report</h1>
                                    <h4 class="text-white fs-13px">{{$reports['user_name']}}
                                        , {{$reports['user_gender'] === '0' ? 'Male' : 'Female'}}, Interval</h4>
                                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-intervel="false">
                                    <a class="carousel-control-prev align-items-start  py-3 py-md-0 mt-n5 " href="#carouselExampleIndicators"
                                           onclick="pauseAllVideos()" role="button"
                                           data-slide="prev" style="height:fit-content;">
                                            <span class="carousel-control-prev-icon "  aria-hidden="true" ></span>
                                            <span class="sr-only">Previous</span>

                                        </a>
                                        <a class="carousel-control-next align-items-start py-3 py-md-0   mt-n5" href="#carouselExampleIndicators"
                                           onclick="pauseAllVideos()" role="button"
                                           data-slide="next" style="height:fit-content;">
                                            <span class="carousel-control-next-icon " aria-hidden="true" ></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                        <div class="carousel-inner  px-0">
                                            <div class="carousel-item active">
                                                <p class="slider-padding">The ULT Performance Report serves to identify
                                                    those aspects about you that define and direct your best performance
                                                    qualities. Since your physical being is respectively the assigned
                                                    vehicle transporting you through this lifetime, it's often helpful
                                                    to know what kind of vehicle you are. The Greeks have been insisting
                                                    we "Know Thyself" for centuries. This simple request answered can
                                                    facilitate success in all aspects of life including one's
                                                    performance in conducting business and creating healthy
                                                    relationships at work and in life. The ULT technology is a patented
                                                    instrument registered and branded as The Ultimate Life Tool. The
                                                    methodology serving as the foundation for its development is
                                                    referred to as The Knowledge of Y.O.U. (your own understanding).
                                                    This cumulative insight is older than the language of man and is
                                                    founded in physical law and scientific objective understanding. The
                                                    ULT assessment tool queries and quantifies information and
                                                    identifies results in a manner that can be easily understood. Your
                                                    personal ULT Performance Report introduces you to Y.O.U. and
                                                    provides you with your own operating manual. These operating
                                                    guidelines support you in making conscious choices in selecting
                                                    opportunities that will naturally advance you in this lifetime. When
                                                    you use your natural talents versus learned talents you gain energy.
                                                    Maximizing your fuel efficiency allows you to access your true self
                                                    and enjoy life in the process.</p>
{{--                                                <button onclick="pauseVideo('myVideo100')" data-toggle="collapse"--}}
{{--                                                        href="#intro_video" role="button"--}}
{{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                    Video--}}
{{--                                                </button>--}}

                                                <button onclick="showVideoAccordingToSlide('{{asset('assets/video/HumanOp ULT Results Intro - Lisa Nelson.mp4')}}')"
                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                    Video
                                                </button>

{{--                                                <div class="collapse" id="intro_video">--}}
{{--                                                    <video id="myVideo100" class="slider-padding mb-5 videoStop"--}}
{{--                                                           width="1100" height="550"--}}
{{--                                                           controls>--}}
{{--                                                        <source--}}
{{--                                                            src="{{asset('assets/video/HumanOp ULT Results Intro - Lisa Nelson.mp4')}}"--}}
{{--                                                            type="video/mp4">--}}
{{--                                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                        Your browser does not support HTML video.--}}
{{--                                                    </video>--}}
{{--                                                </div>--}}
                                            </div>
                                            <div class="carousel-item">
                                                <p class="slider-padding">This advanced human assessment curriculum and
                                                    technology are products of YCG, LLC dba The YOU Institute. The
                                                    curriculum is approved for continuing education by The California
                                                    State Board of Behavioral Sciences, The Board of Registered Nursing
                                                    and the International Coach Federation. The ULT Performance Report
                                                    is helpful to employers and various agencies seeking compatibility
                                                    in people placement as well as professionals trained in relationship
                                                    management and psychotherapy. Your ULT Performance Report adds
                                                    intrinsic value in fortifying relationships, seeking a career,
                                                    preparing for marriage, selecting a roommate and in better
                                                    understanding yourself and others.</p>
{{--                                                <button onclick="pauseVideo('myVideo200')" data-toggle="collapse"--}}
{{--                                                        href="#intro_cycle_video" role="button"--}}
{{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                    Video--}}
{{--                                                </button>--}}
                                                <button onclick="showVideoAccordingToSlide('{{asset('assets/video/Intro to The Cycle of Life.mp4')}}')"
                                                        {{--                                                        href="#intro_cycle_video" role="button"--}}
                                                        {{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                    Video
                                                </button>
{{--                                                <div class="collapse" id="intro_cycle_video">--}}
{{--                                                    <video id="myVideo200" class="slider-padding mb-5 videoStop"--}}
{{--                                                           width="1100" height="550" controls>--}}
{{--                                                        <source--}}
{{--                                                            src="{{asset('assets/video/Intro to The Cycle of Life.mp4')}}"--}}
{{--                                                            type="video/mp4">--}}
{{--                                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                        Your browser does not support HTML video.--}}
{{--                                                    </video>--}}
{{--                                                </div>--}}
                                            </div>
                                            <div class="carousel-item">
                                                <h2 style="color: #1b3a62 " class="text-start ms-md-5 ">The ULT Performance
                                                    Report addresses the
                                                    following:</h2>
                                                <ul class="slider-padding "
                                                    style="">
                                                    <li> Your unique natural expression of self</li>

                                                    <li>Talents that motivate and prompt you to participate in life</li>

                                                    <li> What you can tolerate in terms of people, places and things
                                                    </li>

                                                    <li>How you connect, learn and commit experiences to memory</li>

                                                    <li>Your perception of life that defines your physical reality</li>

                                                    <li> How much energy you currently have available to succeed</li>
                                                </ul>
{{--                                                <button onclick="pauseVideo('myVideo300')" data-toggle="collapse"--}}
{{--                                                        href="#cycle_of_life_video" role="button"--}}
{{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                    Video--}}
{{--                                                </button>--}}
                                                <button onclick="showVideoAccordingToSlide('{{asset('assets/video/Cycle of Life - Awareness Interval 43-52.mp4')}}')"
                                                        {{--                                                        href="#cycle_of_life_video" role="button"--}}
                                                        {{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                    Video
                                                </button>

{{--                                                <div class="collapse" id="cycle_of_life_video">--}}
{{--                                                    <video id="myVideo300" class="slider-padding mb-5 videoStop"--}}
{{--                                                           width="1100" height="550" controls>--}}
{{--                                                        <source--}}
{{--                                                            src="{{asset('assets/video/Cycle of Life - Awareness Interval 43-52.mp4')}}"--}}
{{--                                                            type="video/mp4">--}}
{{--                                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                        Your browser does not support HTML video.--}}
{{--                                                    </video>--}}
{{--                                                </div>--}}
                                            </div>
                                            <div class="carousel-item">
                                                <h2 class="slider-padding" style="color: #1b3a62;">YOUR TRAITS</h2>
                                                <p class="slider-padding">Your natural physical "TRAITS" determine
                                                    how nature shows up in you. These traits assist in providing unique
                                                    insight into
                                                    your capabilities and natural talents.</p>
{{--                                                <button onclick="pauseVideo('myVideo400')" data-toggle="collapse"--}}
{{--                                                        href="#trait_video" role="button"--}}
{{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                    Video--}}
{{--                                                </button>--}}
                                                <button onclick="showVideoAccordingToSlide('{{asset('assets/video/Intro to Traits.mp4')}}')"
                                                        {{--                                                        href="#trait_video" role="button"--}}
                                                        {{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                    Video
                                                </button>
{{--                                                <div class="collapse" id="trait_video">--}}
{{--                                                    <video id="myVideo400" class="slider-padding mb-5 videoStop"--}}
{{--                                                           width="1100" height="550" controls>--}}
{{--                                                        <source src="{{asset('assets/video/Intro to Traits.mp4')}}"--}}
{{--                                                                type="video/mp4">--}}
{{--                                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                        Your browser does not support HTML video.--}}
{{--                                                    </video>--}}
{{--                                                </div>--}}
                                            </div>
                                            @foreach($reports['style_code_details'] as $report)
                                                <div class="carousel-item">
                                                    <h2 class="slider-padding"
                                                        style="color: #1b3a62;">{{$report['public_name']}}</h2>
                                                    <p class="slider-padding">{{$report['text']}}</p>
                                                    <button onclick="showVideoAccordingToSlide('{{asset('assets/video/'. $report['video'])}}')"
                                                            {{--                                                        href="#trait_video" role="button"--}}
                                                            {{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
                                                            class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                            style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                        Video
                                                    </button>
{{--                                                    <button onclick="pauseVideo('myVideo{{$report['id']}}')"--}}
{{--                                                            data-toggle="collapse" href="#{{$report['p_name']}}"--}}
{{--                                                            role="button"--}}
{{--                                                            aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                            class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                            style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                        Video--}}
{{--                                                    </button>--}}
{{--                                                    <div class="collapse" id="{{$report['p_name']}}">--}}
{{--                                                        <video id="myVideo{{$report['id']}}"--}}
{{--                                                               class="slider-padding mb-5 videoStop" width="1100"--}}
{{--                                                               height="550"--}}
{{--                                                               controls>--}}
{{--                                                            <source src="{{asset('assets/video/'. $report['video'])}}"--}}
{{--                                                                    type="video/mp4">--}}
{{--                                                            <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                            Your browser does not support HTML video.--}}
{{--                                                        </video>--}}
{{--                                                    </div>--}}
                                                </div>
                                            @endforeach
                                            <div class="carousel-item">
                                                <h2 class="slider-padding" style="color: #1b3a62;">YOUR MOTIVATION</h2>
                                                <p class="slider-padding"> Your "MOTIVATION" addresses what
                                                    “DRIVES” you, what must be fed and honored so that you
                                                    can successfully reach your destination. There are 12 “DRIVERS” in
                                                    everyone’s
                                                    “vehicle of self”. These
                                                    drivers are all chattering at the same time, but only some are
                                                    licensed to
                                                    drive. Knowing how to keep these
                                                    legally authorized drivers in the front seat and motivated allows
                                                    for efficient
                                                    travel. These driving forces
                                                    represent specific laws of nature that show up in all living things.
                                                    These
                                                    drivers express themselves as
                                                    strengths and weaknesses. It is your personal responsibility to come
                                                    from a
                                                    place of strength. Strength
                                                    transmits intelligence while weakness produces ignorance. Choosing
                                                    opportunities
                                                    that feed your strengths,
                                                    your talents, and your passion, will bring you closer to states of
                                                    intelligence.
                                                    What motivates or drives
                                                    you requires you choose those listed below in order of
                                                    proficiency.</p>
                                                <button onclick="showVideoAccordingToSlide('{{asset('assets/video/Intro to Motivation (Drivers).mp4')}}')"
                                                        {{--                                                        href="#motivation_video" role="button"--}}
                                                        {{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                    Video
                                                </button>
{{--                                                <button onclick="pauseVideo('myVideo111')" data-toggle="collapse"--}}
{{--                                                        href="#motivation_video" role="button"--}}
{{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                    Video--}}
{{--                                                </button>--}}
{{--                                                <div class="collapse" id="motivation_video">--}}
{{--                                                    <video id="myVideo111" class="slider-padding mb-5 videoStop"--}}
{{--                                                           width="1100" height="550" controls>--}}
{{--                                                        <source--}}
{{--                                                            src="{{asset('assets/video/Intro to Motivation (Drivers).mp4')}}"--}}
{{--                                                            type="video/mp4">--}}
{{--                                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                        Your browser does not support HTML video.--}}
{{--                                                    </video>--}}
{{--                                                </div>--}}
                                            </div>
                                            @foreach($reports['feature_code_details'] as $report)
                                                <div class="carousel-item">
                                                    <h2 class="slider-padding"
                                                        style="color: #1b3a62;">{{$report['public_name']}}</h2>
                                                    <p class="slider-padding">{{$report['text']}}</p>
                                                    <button onclick="showVideoAccordingToSlide('{{asset('assets/video/'. $report['video'])}}')"
                                                            {{--                                                    <button onclick="pauseVideo('myVideo{{$report['id']}}')"--}}
                                                            {{--                                                            data-toggle="collapse" href="#{{$report['p_name']}}"--}}
                                                            {{--                                                            role="button"--}}
                                                            {{--                                                            aria-expanded="false" aria-controls="collapseExample"--}}
                                                            class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                            style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                        Video
                                                    </button>
{{--                                                    <button onclick="pauseVideo('myVideo{{$report['id']}}')"--}}
{{--                                                            data-toggle="collapse" href="#{{$report['p_name']}}"--}}
{{--                                                            role="button"--}}
{{--                                                            aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                            class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                            style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                        Video--}}
{{--                                                    </button>--}}
{{--                                                    <div class="collapse" id="{{$report['p_name']}}">--}}
{{--                                                        <video id="myVideo{{$report['id']}}"--}}
{{--                                                               class="slider-padding mb-5 videoStop" width="1100"--}}
{{--                                                               height="550"--}}
{{--                                                               controls>--}}
{{--                                                            <source src="{{asset('assets/video/'. $report['video'])}}"--}}
{{--                                                                    type="video/mp4">--}}
{{--                                                            <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                            Your browser does not support HTML video.--}}
{{--                                                        </video>--}}
{{--                                                    </div>--}}
                                                </div>
                                            @endforeach
                                            <div class="carousel-item">
                                                <h2 class="slider-padding" style="color: #1b3a62;">YOUR BOUNDARIES</h2>
                                                <p class="slider-padding"> “ALCHEMY” addresses your refinement
                                                    preferences; whether you are meticulous, practical,
                                                    messy, and what you can tolerate in others. The Knowledge of Y.O.U.
                                                    uses the
                                                    analogy of ore to exemplify
                                                    states of refinement, specifically Gold, Silver and Copper. Alchemy
                                                    determines
                                                    where your "BOUNDARIES" begin
                                                    and end. This range identifies what you can tolerate in terms of
                                                    people, places
                                                    and things and how to best
                                                    manage your choices in maximizing your energy potential. Alchemical
                                                    incompatibility is the number one reason
                                                    for challenges in relationships. Not addressing boundary issues in
                                                    any
                                                    relationship can result in
                                                    relationship failure. In business and in life it is vital to know
                                                    what your
                                                    personal alchemical range of
                                                    tolerance is so that you can better understand your own boundaries
                                                    and those
                                                    around you.</p>
                                                <button onclick="showVideoAccordingToSlide('{{asset('assets/video/Intro to Alchemy.mp4')}}')"
                                                        {{--                                                        href="#alchemy_video" role="button"--}}
                                                        {{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                    Video
                                                </button>
{{--                                                <button onclick="pauseVideo('myVideo222')" data-toggle="collapse"--}}
{{--                                                        href="#alchemy_video" role="button"--}}
{{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                    Video--}}
{{--                                                </button>--}}
{{--                                                <div class="collapse" id="alchemy_video">--}}
{{--                                                    <video id="myVideo222" class="slider-padding mb-5 videoStop"--}}
{{--                                                           width="1100" height="550" controls>--}}
{{--                                                        <source src="{{asset('assets/video/Intro to Alchemy.mp4')}}"--}}
{{--                                                                type="video/mp4">--}}
{{--                                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                        Your browser does not support HTML video.--}}
{{--                                                    </video>--}}
{{--                                                </div>--}}
                                            </div>
                                            <div class="carousel-item">
                                                <h2 class="slider-padding" style="color: #1b3a62;">YOU HAVE A
                                                    "{{$reports['alchemy_code_details']['public_name']}}"</h2>
                                                @if($reports['alchemy_code_details']['image'] !== null && $reports['alchemy_code_details']['image'] !== 'null')
                                                    <div class="my-3" style="border: 0px solid #ccc;">
                                                        <img
                                                            src="{{asset('assets/'.$reports['alchemy_code_details']['image'])}}"
                                                            style="background:#351a0d; padding: 0px;  border-radius: 5px" class="carousel-image w-60 w-md-50 "/>
                                                    </div>
                                                @endif
                                                <p class="slider-padding">{{$reports['alchemy_code_details']['text']}}</p>
                                                @if($reports['alchemy_code_details']['image'] !== null && $reports['alchemy_code_details']['image'] !== 'null')
                                                    <button
                                                        {{--                                                        onclick="pauseVideo('myVideo{{$reports['alchemy_code_details']['id']}}')"--}}
                                                        onclick="showVideoAccordingToSlide('{{asset('assets/video/'. $reports['alchemy_code_details']['video'])}}')"
                                                        {{--                                                        data-toggle="collapse"--}}
                                                        {{--                                                        href="#{{$reports['alchemy_code_details']['p_name']}}"--}}
                                                        {{--                                                        role="button"--}}
                                                        {{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                        Video
                                                    </button>
{{--                                                    <button--}}
{{--                                                        onclick="pauseVideo('myVideo{{$reports['alchemy_code_details']['id']}}')"--}}
{{--                                                        data-toggle="collapse"--}}
{{--                                                        href="#{{$reports['alchemy_code_details']['p_name']}}" role="button"--}}
{{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                        Video--}}
{{--                                                    </button>--}}
{{--                                                    <div class="collapse"--}}
{{--                                                         id="{{$reports['alchemy_code_details']['p_name']}}">--}}
{{--                                                        <video id="myVideo{{$reports['alchemy_code_details']['id']}}"--}}
{{--                                                               class="slider-padding mb-5 videoStop" width="1100"--}}
{{--                                                               height="550" controls--}}
{{--                                                        >--}}
{{--                                                            <source--}}
{{--                                                                src="{{asset('assets/video/'. $reports['alchemy_code_details']['video'])}}"--}}
{{--                                                                type="video/mp4">--}}
{{--                                                            <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                            Your browser does not support HTML video.--}}
{{--                                                        </video>--}}
{{--                                                    </div>--}}
                                                @endif
                                            </div>
                                            <div class="carousel-item">
                                                <h2 class="slider-padding" style="color: #1b3a62;">YOUR COMMUNICATION
                                                    STYLE</h2>
                                                <p class="slider-padding">“ENERGY CENTERS” define your
                                                    "COMMUNICATION STYLE" and they determine how you uniquely
                                                    relate, connect and learn from your environment. They are
                                                    responsible for how
                                                    every individual commits
                                                    information and experiences to memory. There are four centers:
                                                    Intellectual,
                                                    Moving, Emotional and
                                                    Instinctual. Your pronounced center of energy largely determines how
                                                    you
                                                    initially connect with the moment.
                                                    Everyone is different, and knowing this information can be vital in
                                                    communicating and connecting effectively
                                                    with the world in which we live. The centers are listed below from
                                                    most
                                                    prominent to least prominent in you.
                                                    They exemplify the doors to your house of self. The first door
                                                    represents the
                                                    front door. Opening this door
                                                    is essential in initiating the process of accessing those that
                                                    follow. When all
                                                    are open, a memory is
                                                    established.</p>
                                                <button onclick="showVideoAccordingToSlide('{{asset('assets/video/Intro to Communication Style.mp4')}}')"
                                                        {{--                                                        href="#communication_video" role="button"--}}
                                                        {{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                    Video
                                                </button>
{{--                                                <button onclick="pauseVideo('myVideo333')" data-toggle="collapse"--}}
{{--                                                        href="#communication_video" role="button"--}}
{{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                    Video--}}
{{--                                                </button>--}}
{{--                                                <div class="collapse" id="communication_video">--}}
{{--                                                    <video id="myVideo333" class="slider-padding mb-5 videoStop"--}}
{{--                                                           width="1100" height="550" controls>--}}
{{--                                                        <source--}}
{{--                                                            src="{{asset('assets/video/Intro to Communication Style.mp4')}}"--}}
{{--                                                            type="video/mp4">--}}
{{--                                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                        Your browser does not support HTML video.--}}
{{--                                                    </video>--}}
{{--                                                </div>--}}
                                            </div>
                                            @foreach($reports['communication_code_details'] as $key => $report)
                                                <div class="carousel-item">
                                                    @if($key == 0)
                                                        <h2 class="slider-padding"
                                                            style="color: #1b3a62;">YOU are
                                                            primarily {{$report['public_name']}} centered</h2>
                                                    @elseif($key == 1)
                                                        <h2 class="slider-padding"
                                                            style="color: #1b3a62;">YOU are
                                                            secondly {{$report['public_name']}} centered</h2>
                                                    @elseif($key == 2)
                                                        <h2 class="slider-padding"
                                                            style="color: #1b3a62;">YOU are
                                                            thirdly {{$report['public_name']}} centered</h2>
                                                    @else
                                                        <h2 class="slider-padding"
                                                            style="color: #1b3a62;">YOU are
                                                            lastly {{$report['public_name']}} centered</h2>
                                                    @endif
                                                    <p class="slider-padding">{{$report['text']}}</p>
                                                        <button onclick="showVideoAccordingToSlide('{{asset('assets/video/'. $report['video'])}}')"
                                                                {{--                                                        href="#communication_video" role="button"--}}
                                                                {{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
                                                                class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                                style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                            Video
                                                        </button>
{{--                                                    <button onclick="pauseVideo('myVideo{{$report['id']}}')"--}}
{{--                                                            data-toggle="collapse" href="#{{$report['p_name']}}"--}}
{{--                                                            role="button"--}}
{{--                                                            aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                            class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                            style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                        Video--}}
{{--                                                    </button>--}}
{{--                                                    <div class="collapse" id="{{$report['p_name']}}">--}}
{{--                                                        <video id="myVideo{{$report['id']}}"--}}
{{--                                                               class="slider-padding mb-5 videoStop" width="1100"--}}
{{--                                                               height="550"--}}
{{--                                                               controls>--}}
{{--                                                            <source src="{{asset('assets/video/'. $report['video'])}}"--}}
{{--                                                                    type="video/mp4">--}}
{{--                                                            <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                            Your browser does not support HTML video.--}}
{{--                                                        </video>--}}
{{--                                                    </div>--}}
                                                </div>
                                            @endforeach
                                            <div class="carousel-item">
                                                <h2 class="slider-padding" style="color: #1b3a62;">YOUR PERCEPTION OF
                                                    LIFE</h2>
                                                <p class="slider-padding">{{$reports['perception_life']['text']}}</p>
                                                <button onclick="showVideoAccordingToSlide('{{asset('assets/video/Perception of Life Intro.mp4')}}')"
                                                        {{--                                                        href="#perception_video" role="button"--}}
                                                        {{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                    Video
                                                </button>
{{--                                                <button onclick="pauseVideo('myVideo444')" data-toggle="collapse"--}}
{{--                                                        href="#perception_video" role="button"--}}
{{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                    Video--}}
{{--                                                </button>--}}
{{--                                                <div class="collapse" id="perception_video">--}}
{{--                                                    <video id="myVideo444" class="slider-padding mb-5 videoStop"--}}
{{--                                                           width="1100" height="550" controls>--}}
{{--                                                        <source--}}
{{--                                                            src="{{asset('assets/video/Perception of Life Intro.mp4')}}"--}}
{{--                                                            type="video/mp4">--}}
{{--                                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                        Your browser does not support HTML video.--}}
{{--                                                    </video>--}}
{{--                                                </div>--}}
                                            </div>
                                            <div class="carousel-item">
                                                <h2 class="slider-padding"
                                                    style="color: #1b3a62;">{{$reports['polarity_code_detail']['public_name']}}</h2>
                                                <p class="slider-padding">{{$reports['polarity_code_detail']['text']}}</p>
                                                <button
                                                    {{--                                                    onclick="pauseVideo('myVideo{{$reports['polarity_code_detail']['id']}}')"--}}
                                                    onclick="showVideoAccordingToSlide('{{asset('assets/video/'. $reports['polarity_code_detail']['video'])}}')"
                                                    {{--                                                    data-toggle="collapse"--}}
                                                    {{--                                                    href="#{{$reports['polarity_code_detail']['p_name']}}" role="button"--}}
                                                    {{--                                                    aria-expanded="false" aria-controls="collapseExample"--}}
                                                    class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                    style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                    Video
                                                </button>
{{--                                                <button--}}
{{--                                                    onclick="pauseVideo('myVideo{{$reports['polarity_code_detail']['id']}}')"--}}
{{--                                                    data-toggle="collapse"--}}
{{--                                                    href="#{{$reports['polarity_code_detail']['p_name']}}" role="button"--}}
{{--                                                    aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                    class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                    style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                    Video--}}
{{--                                                </button>--}}
{{--                                                <div class="collapse"--}}
{{--                                                     id="{{$reports['polarity_code_detail']['p_name']}}">--}}
{{--                                                    <video id="myVideo{{$reports['polarity_code_detail']['id']}}"--}}
{{--                                                           class="slider-padding mb-5 videoStop" width="1100"--}}
{{--                                                           height="550" controls>--}}
{{--                                                        <source--}}
{{--                                                            src="{{asset('assets/video/'. $reports['polarity_code_detail']['video'])}}"--}}
{{--                                                            type="video/mp4">--}}
{{--                                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                        Your browser does not support HTML video.--}}
{{--                                                    </video>--}}
{{--                                                </div>--}}
                                            </div>
                                            <div class="carousel-item">
                                                <h2 class="slider-padding" style="color: #1b3a62;">YOUR ENERGY POOL</h2>
                                                <p class="slider-padding">Your “ENERGY POOL” represents how much
                                                    physical energy you have to expend on a daily
                                                    basis. Much like staying hydrated, you must guard its appropriation
                                                    of use. Some activities, choices,
                                                    people, places and things can rob you of vital energy, and depending
                                                    upon the nature of those things or
                                                    choices, you may or may not be able to recoup the energy. Throughout
                                                    life your goal is to maintain an
                                                    average or better volume of energy. This makes life more manageable
                                                    and keeps you from being vulnerable to
                                                    toxic abuse. The need for fortifying your presentation can be met by
                                                    living a more naturally suited
                                                    life.</p>
                                                <button onclick="showVideoAccordingToSlide('{{asset('assets/video/Intro to Energy Pool.mp4')}}')"
                                                        {{--                                                        href="#energy_video" role="button"--}}
                                                        {{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                    Video
                                                </button>
{{--                                                <button onclick="pauseVideo('myVideo555')" data-toggle="collapse"--}}
{{--                                                        href="#energy_video" role="button"--}}
{{--                                                        aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                        class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                        style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                    Video--}}
{{--                                                </button>--}}
{{--                                                <div class="collapse" id="energy_video">--}}
{{--                                                    <video id="myVideo555" class="slider-padding mb-5 videoStop"--}}
{{--                                                           width="1100" height="550" controls>--}}
{{--                                                        <source src="{{asset('assets/video/Intro to Energy Pool.mp4')}}"--}}
{{--                                                                type="video/mp4">--}}
{{--                                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                        Your browser does not support HTML video.--}}
{{--                                                    </video>--}}
{{--                                                </div>--}}
                                            </div>
                                            <div class="carousel-item">
                                                <h2 class="slider-padding"
                                                    style="color: #1b3a62;">{{$reports['energy_code_detail']['public_name']}}</h2>
                                                <p class="slider-padding">{{$reports['energy_code_detail']['text']}}</p>
                                                <button
                                                    onclick="showVideoAccordingToSlide('{{asset('assets/video/'. $reports['energy_code_detail']['video'])}}')"
                                                    {{--                                                    onclick="pauseVideo('myVideo{{$reports['energy_code_detail']['id']}}')"--}}
                                                    {{--                                                    data-toggle="collapse"--}}
                                                    {{--                                                    href="#{{$reports['energy_code_detail']['p_name']}}" role="button"--}}
                                                    {{--                                                    aria-expanded="false" aria-controls="collapseExample"--}}
                                                    class="btn btn-sm float-center mt-4 mb-4 text-white"
                                                    style="background-color: #1b3a62; padding: 12px 63px;">Watch
                                                    Video
                                                </button>
{{--                                                <button--}}
{{--                                                    onclick="pauseVideo('myVideo{{$reports['energy_code_detail']['id']}}')"--}}
{{--                                                    data-toggle="collapse"--}}
{{--                                                    href="#{{$reports['energy_code_detail']['p_name']}}" role="button"--}}
{{--                                                    aria-expanded="false" aria-controls="collapseExample"--}}
{{--                                                    class="btn btn-sm float-center mt-4 mb-4 text-white"--}}
{{--                                                    style="background-color: #1b3a62; padding: 12px 63px;">Watch--}}
{{--                                                    Video--}}
{{--                                                </button>--}}
{{--                                                <div class="collapse" id="{{$reports['energy_code_detail']['p_name']}}">--}}
{{--                                                    <video id="myVideo{{$reports['energy_code_detail']['id']}}"--}}
{{--                                                           class="slider-padding mb-5 videoStop" width="1100"--}}
{{--                                                           height="550" controls>--}}
{{--                                                        <source--}}
{{--                                                            src="{{asset('assets/video/'. $reports['energy_code_detail']['video'])}}"--}}
{{--                                                            type="video/mp4">--}}
{{--                                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                                        Your browser does not support HTML video.--}}
{{--                                                    </video>--}}
{{--                                                </div>--}}

                                            </div>
                                            <div class="carousel-item">
                                                <p class="slider-padding">
                                                    The results documented in your Performance Report address the
                                                    uniqueness and perfection of YOU. We call this Report
                                                    Your Operating Manual.<br/><br/>


                                                    Your physicality, or physical traits, reveal your natural talents
                                                    and capabilities which determine your innate
                                                    potential. They reveal what drives you and how to fuel this drive;
                                                    how your personal preferences impact your
                                                    boundaries of tolerance for people and places; how you specifically
                                                    connect with your environment, relate to others
                                                    and download information and how your unique perception of life has
                                                    an electromagnetic impact on all of your
                                                    relationships.<br/><br/>


                                                    Your Operating Manual identifies what is required for you to
                                                    maintain a healthy state. Because the Ultimate Life
                                                    Tool technology is so multifaceted and multidimensional, to maximize
                                                    the value of your Operating Manual or if you
                                                    have questions about your results, we invite you to contact the
                                                    certified practitioner below for a personal
                                                    consultation.<br/><br/>


                                                    Your personal consultation will identify areas that need and require
                                                    your focus and attention in order to reach your
                                                    full potential on a daily basis. It will also provide you with
                                                    specific strategies to help you integrate and apply
                                                    the information from your Operating Manual in every aspect of your
                                                    life.<br/><br/>


                                                    For services and certification contact your ULT assessment provider.
                                                </p>
                                                <p class="slider-padding">Your practitioner
                                                    is {{\Illuminate\Support\Facades\Auth::user()['first_name']}} {{\Illuminate\Support\Facades\Auth::user()['last_name']}}
                                                    <br>{{\Illuminate\Support\Facades\Auth::user()['email']}}</p>
                                            </div>
                                            <div class="carousel-item">
                                                <p class="slider-padding">For internal use only. <br>Compatibility
                                                    values for
                                                    BR {{\Illuminate\Support\Facades\Auth::user()['gender'] == 1 ? '(F)' : '(M)'}}
                                                    Interval</p>

                                                <p class="slide-padding text-start m-0 ms-md-5 p-0 ps-md-5">S {{$style_position}}</p>
                                                <p class="slide-padding text-start m-0 ms-md-5 p-0 ps-md-5">F {{$feature_position}}</p>
                                                <p class="slide-padding text-start m-0 ms-md-5 p-0 ps-md-5">Alch {{$alchl_code}}</p>
                                                <p class="slide-padding text-start m-0 ms-md-5 p-0 ps-md-5">
                                                    PV {{$reports['pv'] > 0 ? '+' : ''}} {{$reports['pv']}} REP
                                                    ARC {{$reports['pv'] - $reports['ep']}} to
                                                    +{{$reports['pv'] + $reports['ep']}}</p>
                                                <p class="slide-padding text-start m-0 ms-md-5 p-0 ps-md-5">REP {{$reports['ep']}}</p>
                                                <p class="slide-padding text-start m-0 ms-md-5 p-0 ps-md-5">TEP {{$reports['ep'] * 2}}</p>
                                            </div>
                                        </div>


                                        <div class="d-none" id="slide_video_player">
                                            <div class="container show-controls" id="container_video">
                                                <div class="wrapper mx-auto w-75">
                                                    <div class="video-timeline">
                                                        <div class="progress-area">
                                                            <span id="progree-area-span">00:00</span>
                                                            <div class="progress-bar" style="color: #1b3a62;"></div>
                                                        </div>
                                                    </div>
                                                    <ul class="video-controls">
                                                        <li class="options left">
                                                            <button class="volume"><i class="fa-solid fa-volume-high"
                                                                                      style="color: #1b3a62"></i></button>
                                                            <input type="range" min="0" max="1" step="any">
                                                            <div class="video-timer">
                                                                <span class="current-time" style="color: #1b3a62;">00:00</span>
                                                                <span class="separator" style="color: #1b3a62;"> / </span>
                                                                <span class="video-duration" style="color: #1b3a62;">00:00</span>
                                                            </div>
                                                        </li>
                                                        <li class="options center">
                                                            <button class="skip-backward"><i class="fas fa-backward"
                                                                                             style="color: #1b3a62;"></i></button>
                                                            <button class="play-pause"><i class="fas fa-play" style="color: #1b3a62;"></i>
                                                            </button>
                                                            <button class="skip-forward"><i class="fas fa-forward"
                                                                                            style="color: #1b3a62;"></i></button>
                                                        </li>
                                                        <li class="options right">
                                                            <button class="fullscreen"><i class="fa-solid fa-expand"
                                                                                          style="color: #1b3a62;"></i></button>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <video id="video" class="w-100 h-100" style="max-height: 500px;"></video>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 200px">
        <div class="col-12">
            <div id="globe" class="position-absolute end-0 top-2 mt-sm-3 me-lg-7" style="z-index: -1">
                <canvas width="700" height="600" class="w-lg-100 h-lg-100 w-75 h-75 me-lg-0 me-n10 mt-lg-5"></canvas>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('.carousel').carousel({
            interval: false,
        });
    </script>
    <script>
        // Get all video elements
        var videos = document.querySelectorAll('.videoStop');

        // Function to pause all videos except the one with the given ID
        function pauseAllVideos() {
            videos.forEach(function (video) {
                video.pause();
            });
        }

        // Function to toggle video play/pause on button click
        function pauseVideo(videoId) {
            var video = document.getElementById(videoId);
            if (video.paused) {
                pauseAllVideos(videoId); // Pause all other videos
                video.play();
            } else {
                video.pause();
            }
        }

        // Pause all videos when carousel controls are clicked
        document.querySelector('.carousel-control-prev').addEventListener('click', function () {
            pauseAllVideos();

            $('#slide_video_player').removeClass('show');
            $('#slide_video_player').addClass('d-none');
        });
        document.querySelector('.carousel-control-next').addEventListener('click', function () {
            pauseAllVideos();

            $('#slide_video_player').removeClass('show');
            $('#slide_video_player').addClass('d-none');
        });

        function showVideoAccordingToSlide(src){

            if ($('#slide_video_player').hasClass('show')){

                $('#slide_video_player').removeClass('show');
                $('#slide_video_player').addClass('d-none');


            }else {

                $('#slide_video_player').removeClass('d-none');
                $('#slide_video_player').addClass('show');
            }

            $('#video').attr('src', src);
        }

    </script>

    {{--    video player script--}}
    <script>

        const container = document.querySelector("#container_video"),
            // blurvid = document.querySelector("video"),
            mainVideo = container.querySelector("video"),
            videoTimeline = container.querySelector(".video-timeline"),
            progressBar = container.querySelector(".progress-bar"),
            volumeBtn = container.querySelector(".volume i"),
            volumeSlider = container.querySelector(".left input");
        currentVidTime = container.querySelector(".current-time"),
            videoDuration = container.querySelector(".video-duration"),
            skipBackward = container.querySelector(".skip-backward i"),
            skipForward = container.querySelector(".skip-forward i"),
            playPauseBtn = container.querySelector(".play-pause i"),
            speedBtn = container.querySelector(".playback-speed span"),
            speedOptions = container.querySelector(".speed-options"),
            fullScreenBtn = container.querySelector(".fullscreen i");
        let timer;

        const hideControls = () => {
            if (mainVideo.paused) return;
            timer = setTimeout(() => {
                container.classList.remove("show-controls");
            }, 3000);
        }
        hideControls();
        // blurvid.volume = 0;
        container.addEventListener("mousemove", () => {
            container.classList.add("show-controls");
            clearTimeout(timer);
            hideControls();
        });

        const formatTime = time => {
            let seconds = Math.floor(time % 60),
                minutes = Math.floor(time / 60) % 60,
                hours = Math.floor(time / 3600);

            seconds = seconds < 10 ? `0${seconds}` : seconds;
            minutes = minutes < 10 ? `0${minutes}` : minutes;
            hours = hours < 10 ? `0${hours}` : hours;

            if (hours == 0) {
                return `${minutes}:${seconds}`
            }
            return `${hours}:${minutes}:${seconds}`;
        }

        var onClick, current;

        videoTimeline.addEventListener("mousemove", e => {
            let timelineWidth = videoTimeline.clientWidth;
            let offsetX = e.offsetX;
            let percent = Math.floor((offsetX / timelineWidth) * mainVideo.duration);
            const progressTime = videoTimeline.querySelector("#progree-area-span");
            offsetX = offsetX < 20 ? 20 : offsetX > timelineWidth - 20 ? timelineWidth - 20 : offsetX;
            progressTime.style.left = `${offsetX}px`;
            current = percent;
            progressTime.innerText = formatTime(percent);
        });

        videoTimeline.addEventListener("click", e => {

            let timelineWidth = videoTimeline.clientWidth;
            mainVideo.currentTime = (e.offsetX / timelineWidth) * mainVideo.duration;

            // blurvid.currentTime = (e.offsetX / timelineWidth) * mainVideo.duration;
        });

        mainVideo.addEventListener("timeupdate", e => {

            let {currentTime, duration} = e.target;

            let percent = (currentTime / duration) * 100;
            progressBar.style.width = `${percent}%`;
            currentVidTime.innerText = formatTime(currentTime);

        });

        mainVideo.addEventListener("loadeddata", () => {
            videoDuration.innerText = formatTime(mainVideo.duration);
        });

        const draggableProgressBar = e => {
            let timelineWidth = videoTimeline.clientWidth;
            progressBar.style.width = `${e.offsetX}px`;
            mainVideo.currentTime = (e.offsetX / timelineWidth) * mainVideo.duration;
            // blurvid.currentTime = (e.offsetX / timelineWidth) * mainVideo.duration;
            currentVidTime.innerText = formatTime(mainVideo.currentTime);
        }

        volumeBtn.addEventListener("click", () => {
            if (!volumeBtn.classList.contains("fa-volume-high")) {
                mainVideo.volume = 0.5;
                volumeBtn.classList.replace("fa-volume-xmark", "fa-volume-high");
            } else {
                mainVideo.volume = 0.0;
                volumeBtn.classList.replace("fa-volume-high", "fa-volume-xmark");
            }
            volumeSlider.value = mainVideo.volume;
        });

        volumeSlider.addEventListener("input", e => {
            mainVideo.volume = e.target.value;
            if (e.target.value == 0) {
                return volumeBtn.classList.replace("fa-volume-high", "fa-volume-xmark");
            }
            volumeBtn.classList.replace("fa-volume-xmark", "fa-volume-high");
        });

        // speedOptions.querySelectorAll("li").forEach(option => {
        //     option.addEventListener("click", () => {
        //         mainVideo.playbackRate = option.dataset.speed;
        //         // blurvid.playbackRate = option.dataset.speed;
        //         speedOptions.querySelector(".active").classList.remove("active");
        //         option.classList.add("active");
        //     });
        // });

        // document.addEventListener("click", e => {
        //     if(e.target.tagName !== "SPAN" || e.target.className !== "material-symbols-rounded") {
        //         speedOptions.classList.remove("show");
        //     }
        // });

        fullScreenBtn.addEventListener("click", () => {
            container.classList.toggle("fullscreen");
            if (document.fullscreenElement) {
                fullScreenBtn.classList.replace("fa-compress", "fa-expand");
                return document.exitFullscreen();
            }
            fullScreenBtn.classList.replace("fa-expand", "fa-compress");
            container.requestFullscreen();
        });

        // speedBtn.addEventListener("click", () => speedOptions.classList.toggle("show"));
        skipBackward.addEventListener("click", () => {
            mainVideo.currentTime -= 5;
        });
        skipForward.addEventListener("click", () => {
            mainVideo.currentTime += 5
        });
        mainVideo.addEventListener("play", () => playPauseBtn.classList.replace("fa-play", "fa-pause"));
        mainVideo.addEventListener("pause", () => playPauseBtn.classList.replace("fa-pause", "fa-play"));
        playPauseBtn.addEventListener("click", () => {
            mainVideo.paused ? mainVideo.play() : mainVideo.pause()
        });
        videoTimeline.addEventListener("mousedown", () => videoTimeline.addEventListener("mousemove", draggableProgressBar));
        document.addEventListener("mouseup", () => videoTimeline.removeEventListener("mousemove", draggableProgressBar));

    </script>
@endpush

