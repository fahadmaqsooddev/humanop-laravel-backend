@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])
<style>

    /*body {*/
    /*    background-color: #2E9CA8 !important;*/
    /*}*/

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
    / background: #000;
    / aspect-ratio: 16 / 9;
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

    /
    .playback-content {
    /
        /*    display: flex;*/
        /*    position: relative;*/
    /
    }

    /

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

    /
    .playback-content .speed-options.show {
    /
        /*    opacity: 1;*/
        /*    pointer-events: auto;*/
    /
    }

    /
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

        .speed-options li {
            /*    margin: 1px 0;*/
            /*    padding: 3px 0 3px 10px;*/
        }

        .right .pic-in-pic {
            display: none;
        }
    }

    @media screen and (min-width: 300px) and (max-width: 800px) {
        .library_resource_coming_soon {
            padding: 0 40px 50px 10px;
            font-size: 40px;
        }
    }
</style>
@section('content')

    <div class="row container-fluid">
        <div class="col-lg-6 position-relative z-index-2">
            <div class="mb-4">
                <div class="card-Thanks for being body p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-column h-100">
                                <h3 style="color: #1c3e6d" class="font-weight-bolder mb-0">Feedback</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @livewire('client.feedback.feedback-form')

        </div>


        <div class="col-6 col-md-6 col-sm-12 col-12">
            <div id="globe" class="position-relative d-flex justify-content-center" style="height: 600px;">
                <canvas width="650" height="650" class=" h-lg-100 w-75 h-75"></canvas>
                <img width="350" height="350" src="{{asset('assets/img/icons/white-icon.png')}}"
                     class="position-absolute"
                     style="top: 38%; left: 50%; transform: translate(-50%, -50%);opacity: 30%">
            </div>
        </div>
    </div>

    <div class="row mt-4" style="display: none;">
        <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
        <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
    </div>
@endsection
