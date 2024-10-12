@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')

    <style>

        body{
            background-color: #2E9CA8 !important;
        }

        /* Import Google font - Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        .video-container,
        .video-controls,
        .video-timer,
        .options {
            display: flex;
            align-items: center;
            justify-content: center;
        }



        .center-play-pause {
            /* padding-bottom: 150px; */
            /* position: absolute; */

            /* padding-left:20px ; */
        }

        button.play-pause-center {
            color: rgb(210, 102, 34);
            font-size: 6rem;
            padding: 0 0 15% 0;

            background-color: none !important;
            box-shadow: none !important;
            /* padding-bottom:40px ; */
        }

        .video-container {
            width: 100%;
            user-select: none;
            overflow: hidden;
            max-width: 100%;
            border-radius: 5px;
        /background: #000;/
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
            /* background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent); */
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

        .progress-bar::before,
        .progress-area span {
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

        /.playback-content {/
             /*    display: flex;*/
             /*    position: relative;*/
         /}/

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

        /.playback-content .speed-options.show {/
             /*    opacity: 1;*/
             /*    pointer-events: auto;*/
         /}/
        .video-container video {
            width: 120%;
        }

        @media screen and (min-width: 768px) {
            button.play-pause-center {
                font-size: 6rem !important;

            }

            .center-play-pause {
                bottom: 300px;
                left: 20px;

            }
        }

        @media screen and (max-width: 575px) {


            .center-play-pause {
                /* padding-bottom: 20px; */

            }

            button.play-pause-center {
                padding: 0 0 5% 0;

                /* font-size: 2rem; */

            }

            .wrapper .video-controls {
                padding: 3px 10px 7px;
                margin-bottom: 0;
            }

            .options input,
            .progress-area span {
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

            .options .video-timer,
            .progress-area span,
            .speed-options li {
                font-size: 12px;
            }

        .playback-content .speed-options {
             /*    width: 75px;*/
             /*    left: -30px;*/
             /*    bottom: 30px;*/
         }

        .speed-options li{
             /*    margin: 1px 0;*/
             /*    padding: 3px 0 3px 10px;*/
         }
            .right .pic-in-pic {
                display: none;
            }
        }

        @media screen and (min-width: 300px) and (max-width: 800px){
            .library_resource_coming_soon{
                padding: 0 40px 50px 10px;
                font-size: 40px;
            }
        }
    </style>


{{--    <div class="row container-fluid">--}}
{{--        <div class="col-lg-12 position-relative z-index-2">--}}
{{--            <div class="mb-4">--}}
{{--                <div class="card-body p-3">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-lg-12">--}}
{{--                            <div class="d-flex flex-column h-100">--}}
{{--                                <h2 class="font-weight-bolder mb-0">Library Resources</h2>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="row">--}}

{{--                <div class="text-center w-100 p-5">--}}
{{--                    for diagonal styling--}}
{{--                    style="display: inline-block; transform: rotate(-40deg); font-size: 24px; font-weight: bold; color: black;"--}}

{{--                    <h1 style="color: #0f1534;">Coming Soon!</h1>--}}

{{--                </div>--}}

{{--                @foreach($categories as $category)--}}
{{--                    <div class="col-lg-5 col-sm-5">--}}
{{--                        <a data-toggle="collapse" data-target="#collapse-{{$category->name}}" aria-expanded="false" aria-controls="collapse-{{$category->name}}" style="cursor: pointer;">--}}
{{--                            <div class="card mb-4"--}}
{{--                                 >--}}
{{--                                <div class="card-body p-3">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-8 m-auto">--}}
{{--                                            <div class="numbers">--}}
{{--                                                <p class="text-sm mb-0 text-capitalize font-weight-bold"--}}
{{--                                                   style="color: white;">{{$category['name'] ?? null}}</p>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-4 text-end">--}}
{{--                                            <div--}}
{{--                                                class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">--}}
{{--                                                <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                    </div>--}}

{{--                    <div class="col-12">--}}

{{--                        <div class="collapse pb-3" id="collapse-{{$category->name}}">--}}
{{--                            <div class="card-body p-3">--}}
{{--                                <div class="row">--}}

{{--                                    @foreach($category['libraryResources'] as $resource)--}}

{{--                                        <div class="col-lg-5 col-sm-5">--}}
{{--                                            <a onclick="showModal('{{$resource['photo_url']['url'] ?? null}}','{{$resource['video_url']['path'] ?? null}}', '{{$resource['description']}}')" style="cursor: pointer;">--}}
{{--                                                <div class="card mb-4"--}}
{{--                                                     >--}}
{{--                                                    <div class="card-body p-3">--}}
{{--                                                        <div class="row">--}}
{{--                                                            <div class="col-8 m-auto">--}}
{{--                                                                <div class="numbers">--}}
{{--                                                                    <p class="text-sm mb-0 text-capitalize font-weight-bold"--}}
{{--                                                                       style="color: white;">{{$resource['heading'] ?? null}}</p>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="col-4 text-end">--}}
{{--                                                                <div--}}
{{--                                                                    class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">--}}
{{--                                                                    <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </a>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                @endforeach--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <button data-bs-toggle="modal"
            data-bs-target="#life" hidden>
        modal button
    </button>
    <div class="modal fade" id="life" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="d-flex align-items-center justify-content-center h-100" style="z-index: 1000">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px; width: 900px;">
                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <br>
                        <br>

                        <div class="w-100">
                            <p class="text-white text-sm" id="resource_text"></p>
                        </div>

                        <div class="video-container show-controls" id="container_video">
                            <div class="wrapper mx-auto w-75 ">
                                <div
                                    class="center-play-pause d-flex align-items-center justify-content-center h-75 w-100   mx-auto">
                                    <button class="btn play-pause-center fs-1"
                                            style="color: rgb(210, 102, 34);"><i class="fas fa-play"></i>
                                    </button>
                                </div>
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
                                        <button class="play-pause"><i class="fas fa-play"
                                                                      style="color: #f2661c;"></i>
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
                            <video id="video" class="w-100 h-100 " style="max-height: 500px;"></video>
                        </div>

                        <div class="container" id="container_image">

                            <img src="" id="image" class="img-fluid w-100 h-100">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4" style="display: none;">
        <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
        <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
    </div>

    <div class="row">
        <div class="col-2 col-md-2 col-sm-0 text-center mt-5 pt-5 col-0">

        </div>
        <div class="col-8 col-md-4 col-sm-12 col-m-12 mt-5 d-flex justify-content-end" style="padding-top: 5rem;">
            <h1 class="pt-5 library_resource_coming_soon" style="color: #0f1534;">Coming Soon!</h1>
        </div>
        <div class="col-6 col-md-6  col-sm-12 col-12">
            <div id="globe" class="position-relative d-flex justify-content-center" style="height: 600px;">
                <canvas width="650" height="650" class=" h-lg-100 w-75 h-75"></canvas>
                <img width="350" height="350" src="{{asset('assets/img/icons/white-icon.png')}}" class="position-absolute" style="top: 38%; left: 50%; transform: translate(-50%, -50%);opacity: 30%">
            </div>
        </div>
    </div>

    {{--    <a class="git-icon" href="https://github.com/vaibhav1663" target="_blank">--}}
    {{--        <img src="https://github.com/fluidicon.png" alt="">--}}
    {{--    </a>--}}
    {{--    <video src="https://vaibhav1663.github.io/Youtube-Ambient-Mode/demo-video.mp4" poster="https://vaibhav1663.github.io/Youtube-Ambient-Mode/poster.jpg" id="blurred"></video>--}}


@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modals = [
                {id: 'life', videoId: 'video'}
            ];

            modals.forEach(function (modal) {
                var modalElement = document.getElementById(modal.id);
                var videoElement = document.getElementById(modal.videoId);

                modalElement.addEventListener('hide.bs.modal', function () {
                    if (videoElement) {
                        videoElement.pause();
                    }
                });
            });
        });

        function showModal(img_src, video_src, description = null) {

            if (img_src){

                $('#life').modal('show');

                $('#container_video').hide();

                $('#container_image').show();

                // $('#video').attr('src', "https://vaibhav1663.github.io/Youtube-Ambient-Mode/demo-video.mp4");
                $('#image').attr('src', img_src);

                $('#resource_text').html(description)

            }else if (video_src){

                $('#life').modal('show');

                $('#container_image').hide();

                $('#container_video').show();

                // $('#video').attr('src', "https://vaibhav1663.github.io/Youtube-Ambient-Mode/demo-video.mp4");
                $('#video').attr('src', video_src);

                $('#resource_text').html(description)

            }

        }

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
            playPauseBtnCenter = container.querySelector(".play-pause-center i"),
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

        mainVideo.addEventListener("play", () => playPauseBtnCenter.classList.replace("fa-play", "fa-pause"));
        mainVideo.addEventListener("pause", () => playPauseBtnCenter.classList.replace("fa-pause", "fa-play"));
        playPauseBtnCenter.addEventListener("click", () => {
            mainVideo.paused ? mainVideo.play() : mainVideo.pause()
        });

        videoTimeline.addEventListener("mousedown", () => videoTimeline.addEventListener("mousemove", draggableProgressBar));
        document.addEventListener("mouseup", () => videoTimeline.removeEventListener("mousemove", draggableProgressBar));


        // Play / pause.
        mainVideo.addEventListener('click', function () {
            if (mainVideo.paused == false) {
                mainVideo.pause();
                mainVideo.firstChild.nodeValue = 'Play';
            } else {
                mainVideo.play();
                mainVideo.firstChild.nodeValue = 'Pause';
            }
        });

        // Add event listener for pause
        mainVideo.style.opacity = '0.5';  // Dim the video by changing opacity

        mainVideo.addEventListener('pause', () => {
            mainVideo.style.opacity = '0.5';  // Dim the video by changing opacity
        });

        // Add event listener for play
        mainVideo.addEventListener('play', () => {
            mainVideo.style.opacity = '1';  // Reset the opacity when playing
        });
    </script>

    <script>
        $('.close').click(function () {
            $('#video').each(function () {
                $(this).get(0).pause();
            })
        });
    </script>
@endpush
