@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])
@section('content')

    <style>

        /* Import Google font - Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        .video-container, .video-controls, .video-timer, .options{
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-container {
            width: 100%;
            user-select: none;
            overflow: hidden;
            max-width: 100%;
            border-radius: 5px;
            /*background: #000;*/
            aspect-ratio: 16 / 9;
            position: relative;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .video-container.fullscreen {
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
            width: 1000px;
        }

        .video-container.show-controls .wrapper {
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
            background: rgb(242, 102, 28);
        }

        .progress-bar::before {
            content: "";
            right: 0;
            top: 50%;
            height: 13px;
            width: 13px;
            position: absolute;
            border-radius: 50%;
            background: rgb(242, 102, 28);
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
            accent-color: rgb(242, 102, 28);
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
        .video-container video {
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

    <div class="row">
        <div class="col-lg-12 position-relative z-index-2">
            <div class="container-fluid px-0 px-md-5">

                <section>
                    <div class="row mt-lg-4 mt-2">
                        <div class="col-12">
                            <div class="card px-0" style="text-align: center">
                                <div class="card-body p-3 ">
                                    <h1 class="text-white">Your HumanOp Profile Overview</h1>

{{--                                    video container--}}
                                    <div class="video-container show-controls" id="container_video">
                                        <div class="wrapper mx-auto w-75">
                                            <div class="video-timeline">
                                                <div class="progress-area">
                                                    <span id="progree-area-span">00:00</span>
                                                    <div class="progress-bar" style="color: #f2661c;"></div>
                                                </div>
                                            </div>
                                            <ul class="video-controls">
                                                <li class="options left">
                                                    <button class="volume"><i class="fa-solid fa-volume-high"
                                                                              style="color: rgb(242, 102, 28)"></i></button>
                                                    <input type="range" min="0" max="1" step="any">
                                                    <div class="video-timer">
                                                        <span class="current-time" style="color: #f2661c;">00:00</span>
                                                        <span class="separator" style="color: #f2661c;"> / </span>
                                                        <span class="video-duration" style="color: #f2661c;">00:00</span>
                                                    </div>
                                                </li>
                                                <li class="options center">
                                                    <button class="skip-backward"><i class="fas fa-backward"
                                                                                     style="color: #f2661c;"></i></button>
                                                    <button class="play-pause"><i class="fas fa-play" style="color: #f2661c;"></i>
                                                    </button>
                                                    <button class="skip-forward"><i class="fas fa-forward"
                                                                                    style="color: #f2661c;"></i></button>
                                                </li>
                                                <li class="options right">
                                                    <button class="fullscreen"><i class="fa-solid fa-expand"
                                                                                  style="color: #f2661c;"></i></button>
                                                </li>
                                            </ul>
                                        </div>
                                        <video id="myVideo100" class="w-100 h-100" style="max-height: 500px;"></video>
                                    </div>

{{--                                    <video id="myVideo100" class="slider-padding mb-5 videoStop mt-5" width="1100"--}}
{{--                                           height="400" controls>--}}
{{--                                        <source--}}
{{--                                            src="{{asset('assets/video/HumanOp ULT Results Intro - Lisa Nelson.mp4')}}"--}}
{{--                                            type="video/mp4" id="video-source">--}}
{{--                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                        Your browser does not support HTML video.--}}
{{--                                    </video>--}}
                                    <ul style="justify-content: space-evenly; background-color: transparent; padding-top: 20px;"
                                        class="nav nav-pills">
                                        <li><a href="#summaryReport"
                                               class="flex-sm-fill text-lg-center nav-link text-white text-bold {{request()->has('video_url') ? '' : "active"}}"
                                               data-toggle="tab">Summary Report</a>
                                        </li>
                                        <li><a href="#coreStats" class="flex-sm-fill text-lg-center nav-link text-white {{request()->has('video_url') ? 'active' : ""}}"
                                               data-toggle="tab">Core Stats</a>
                                        </li>
                                        <li><a href="#dayPlan" class="flex-sm-fill text-lg-center nav-link text-white"
                                               data-toggle="tab">90 Days Plan</a>
                                        </li>
                                    </ul>
                                    <div class="container tab-content clearfix">
                                        <div class="tab-pane {{request()->has('video_url') ? '' : "active"}}" id="summaryReport">
                                            <div class="slider-padding p-3 mt-5">
                                                <p>The ULT Performance Report serves to identify
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
                                                <p>This advanced human assessment curriculum and
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
                                                <h4 class="primaryColor">The ULT Performance Report
                                                    addresses the following:</h4>
                                                <ul>
                                                    <li> Your unique natural expression of self</li>

                                                    <li>Talents that motivate and prompt you to participate in life</li>

                                                    <li> What you can tolerate in terms of people, places and things
                                                    </li>

                                                    <li>How you connect, learn and commit experiences to memory</li>

                                                    <li>Your perception of life that defines your physical reality</li>

                                                    <li> How much energy you currently have available to succeed</li>
                                                </ul>

                                                @if($assessment)
                                                    <a href="{{url('client/download-user-report/'. $assessment->id)}}" target="_blank"
                                                       class=" btn updateBtn btn-sm float-start text-white mt-4 mb-0"
                                                       style="background-color: #f2661c">Download Summary Report</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tab-pane {{request()->has('video_url') ? 'active' : ""}}" id="coreStats">

                                            <div class="slider-padding p-3 mt-5">
                                                <h4 class="primaryColor">Profile Overview</h4>
                                                <p class="mt-4">Your HumanOp profile reveals a unique combination of traits that shape your approach to life and work. Here are the key highlights based on your ULT Summary Report:</p>
                                                <div class="row d-flex mt-5">
                                                    @foreach($topThreeStyles as $index => $style)
                                                    <div class="col-lg-4 col-sm-12 col-md-6">
                                                            <div class="card" style="height: auto">
                                                                <div class="card-body p-3 ">
                                                                    <h5 data-toggle="collapse" data-target="#{{$style[1]}}" aria-expanded="true" aria-controls="{{$style[1]}}"
                                                                        onclick="showFeatureVideo('{{$style[3]}}', 1)" style="cursor: pointer;" class="text-white fs-10px">
                                                                        {{$index + 1}}. {{$style[1]}}
                                                                    </h5>
                                                                    <div id="{{$style[1]}}" class="collapse description-container" aria-labelledby="headingOne" data-parent="#accordion">
                                                                        <p class="text-sm mt-3 fs-12px" style="color: rgb(160, 174, 192);">{{$style[2]}}</p>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <div class="row d-flex mt-5">
                                                    @foreach($topTwoFeatures as $index => $feature)
                                                        <div class="col-lg-6 col-sm-12 col-md-6">
                                                            <div class="card" style="height: auto">
                                                                <div class="card-body p-3 ">
                                                                    <h5 data-toggle="collapse" data-target="#{{$feature[1]}}" aria-expanded="true" aria-controls="{{$feature[1]}}"
                                                                        onclick="showFeatureVideo('{{$feature[3]}}', 1)" style="cursor: pointer;" class="text-white fs-10px">
                                                                        {{$index + 1}}. {{$feature[1]}}
                                                                    </h5>
                                                                    <div id="{{$feature[1]}}" class="collapse description-container" aria-labelledby="headingOne" data-parent="#accordion">
                                                                        <p class="text-sm mt-3 fs-12px" style="color: rgb(160, 174, 192);">{{$feature[2]}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="dayPlan">
                                            <div class="slider-padding p-3 mt-5">
                                                <div>
                                                    {!! $actionPlan['plan_text'] ?? null !!}
                                                </div>
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

@push('js')

    <script>

        var video_url = "{{request()->input('video_url', asset('assets/video/HumanOp ULT Results Intro - Lisa Nelson.mp4')) }}";

        showFeatureVideo(video_url);

        function showFeatureVideo(src, is_core_stats = 0){

            var video = document.getElementById('myVideo100');
            var videoContainer = document.getElementById('container_video');
            var video_source = document.getElementById('video-source')
            var source = document.createElement('source');

            if (video_source !== null){

                video_source.remove();
            }

            video.pause();

            source.setAttribute('src', src);
            source.setAttribute('type', 'video/mp4');
            source.setAttribute('id', 'video-source');

            video.appendChild(source);
            video.load();

            videoContainer.scrollIntoView();

            if (is_core_stats){
                video.play();

            }

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

