@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')

    <style>

        /* Import Google font - Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
        /**{*/
        /*    margin: 0;*/
        /*    padding: 0;*/
        /*    box-sizing: border-box;*/
        /*    font-family: 'Poppins', sans-serif;*/
        /*}*/
        .container, .video-controls, .video-timer, .options{
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /*body{*/
        /*    min-height: 100vh;*/
        /*    width: 100vw;*/
        /*    background: #000;*/
        /*    position: fixed;*/
        /*}*/
        /*#blurred{*/
        /*    position: absolute;*/
        /*    align-self: center;*/
        /*    filter: blur(100px);*/
        /*    width: 130%;*/
        /*    max-width: 1200px;*/
        /*    aspect-ratio: 16 / 9;*/
        /*    opacity: 0.5;*/
        /*}*/
        .container{
            width: 98%;
            user-select: none;
            overflow: hidden;
            max-width: 900px;
            border-radius: 5px;
            background: #000;
            aspect-ratio: 16 / 9;
            position: relative;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .container.fullscreen{
            max-width: 100%;
            width: 100%;
            height: 100vh;
            border-radius: 0px;
        }
        .wrapper{
            position: absolute;
            left: 0;
            right: 0;
            z-index: 1;
            opacity: 0;
            bottom: -15px;
            transition: all 0.08s ease;
        }
        .container.show-controls .wrapper{
            opacity: 1;
            bottom: 0;
            transition: all 0.13s ease;
        }
        .wrapper::before{
            content: "";
            bottom: 0;
            width: 100%;
            z-index: -1;
            position: absolute;
            height: calc(100% + 35px);
            pointer-events: none;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        }
        .video-timeline{
            height: 7px;
            width: 100%;
            cursor: pointer;
        }
        .video-timeline .progress-area{
            height: 3px;
            position: relative;
            background: rgba(255, 255, 255, 0.6);
        }
        .progress-area span{
            position: absolute;
            left: 50%;
            top: -25px;
            font-size: 13px;
            color: #fff;
            pointer-events: none;
            transform: translateX(-50%);
        }
        .progress-area .progress-bar{
            width: 0%;
            height: 100%;
            position: relative;
            background: rgb(242, 102, 28);
        }
        .progress-bar::before{
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
        .progress-bar::before, .progress-area span{
            display: none;
        }
        .video-timeline:hover .progress-bar::before,
        .video-timeline:hover .progress-area span{
            display: block;
        }
        .wrapper .video-controls{
            padding: 5px 20px 10px;
        }
        .video-controls .options{
            width: 100%;
        }
        .video-controls .options:first-child{
            justify-content: flex-start;
        }
        .video-controls .options:last-child{
            justify-content: flex-end;
        }
        .options button{
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
        .options button:hover :where(i, span){
            color: #fff;
        }
        .options button:active :where(i, span){
            transform: scale(0.9);
        }
        .options button span{
            font-size: 23px;
        }
        .options input{
            height: 4px;
            margin-left: 3px;
            max-width: 75px;
            accent-color: rgb(242, 102, 28);
        }
        .options .video-timer{
            color: #efefef;
            margin-left: 15px;
            font-size: 14px;
        }
        .video-timer .separator{
            margin: 0 5px;
            font-size: 16px;
            font-family: "Open sans";
        }
        .playback-content{
            display: flex;
            position: relative;
        }
        .playback-content .speed-options{
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
        .playback-content .speed-options.show{
            opacity: 1;
            pointer-events: auto;
        }
        /*.speed-options li{*/
        /*    cursor: pointer;*/
        /*    color: #000;*/
        /*    font-size: 14px;*/
        /*    margin: 2px 0;*/
        /*    padding: 5px 0 5px 15px;*/
        /*    transition: all 0.1s ease;*/
        /*}*/
        /*.speed-options li:where(:first-child, :last-child){*/
        /*    margin: 0px;*/
        /*}*/
        /*.speed-options li:hover{*/
        /*    background: #dfdfdf;*/
        /*}*/
        /*.speed-options li.active{*/
        /*    color: #fff;*/
        /*    background: #3e97fd;*/
        /*}*/
        .container video{
            width: 120%;
        }

        @media screen and (max-width: 540px) {
            .wrapper .video-controls{
                padding: 3px 10px 7px;
            }
            .options input, .progress-area span{
                display: none!important;
            }
            .options button{
                height: 30px;
                width: 30px;
                font-size: 17px;
            }
            .options .video-timer{
                margin-left: 5px;
            }
            .video-timer .separator{
                font-size: 14px;
                margin: 0 2px;
            }
            .options button :where(i, span) {
                line-height: 30px;
            }
            .options button span{
                font-size: 21px;
            }
            .options .video-timer, .progress-area span, .speed-options li{
                font-size: 12px;
            }
            .playback-content .speed-options{
                width: 75px;
                left: -30px;
                bottom: 30px;
            }
            /*.speed-options li{*/
            /*    margin: 1px 0;*/
            /*    padding: 3px 0 3px 10px;*/
            /*}*/
            .right .pic-in-pic{
                display: none;
            }
        }
        /*.blur{*/
        /*    width: 150%;*/
        /*    height: 150%;*/
        /*    background-color: rgba(0,0, 0, 0.5);*/
        /*    filter: blur(25px);*/
        /*    position: absolute;*/
        /*    left: 0;*/
        /*    top: 0;*/
        /*}*/
        /*.git-icon{*/
        /*    position: absolute;*/
        /*    width: 50px;*/
        /*    height: 50px;*/
        /*    background-color: #fff;*/
        /*    right: 20px;*/
        /*    top: 20px;*/
        /*    border-radius: 50%;*/
        /*    display: flex;*/
        /*    align-items: center;*/
        /*    justify-content: center;*/
        /*    box-shadow: #eee 2px 3px 10px;*/
        /*    z-index: 2;*/
        /*}*/
        /*.git-icon img{*/
        /*    height: 30px;*/
        /*    width: 30px;*/
        /*    border-radius: 15px;*/
        /*}*/
        /*.git-icon:hover{*/
        /*    background-color: #DDD;*/
        /*    box-shadow: #eee 3px 5px 10px;*/
        /*}*/

    </style>


    <div class="row container-fluid">
        <div class="col-lg-7 position-relative z-index-2">
            <div class="mb-4">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-column h-100">
                                <h2 class="font-weight-bolder mb-0">Results</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-5 col-sm-5">
{{--                    <a data-bs-toggle="modal"--}}
{{--                       data-bs-target="#introduction">--}}
                    <a onclick="showModal('{{ asset('assets/video/HumanOp ULT Results Intro - Lisa Nelson.mp4') }}')">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Introduction</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
{{--                    <a data-bs-toggle="modal"--}}
{{--                       data-bs-target="#cycle_of_life">--}}
                    <a onclick="showModal('{{ asset('assets/video/Intro to The Cycle of Life.mp4') }}')">
                        <div class="card"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Cycle of Life</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-5 col-sm-5 mt-sm-0 mt-4">
{{--                    <a data-bs-toggle="modal"--}}
{{--                       data-bs-target="#trait">--}}
                    <a onclick="showModal('{{ asset('assets/video/Intro to Traits.mp4') }}')">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Traits</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
{{--                    <a data-bs-toggle="modal"--}}
{{--                       data-bs-target="#motivation">--}}
                    <a onclick="showModal('{{ asset('assets/video/Intro to Motivation (Drivers).mp4') }}')">
                        <div class="card"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Motivational Drivers</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-5 col-sm-5">
{{--                    <a data-bs-toggle="modal"--}}
{{--                       data-bs-target="#alchemy">--}}
                    <a onclick="showModal('{{ asset('assets/video/Intro to Alchemy.mp4') }}')">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                Alchemic Boundaries</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
{{--                    <a data-bs-toggle="modal"--}}
{{--                       data-bs-target="#energy">--}}
                    <a onclick="showModal('{{ asset('assets/video/Intro to Energy Pool.mp4') }}')">
                        <div class="card"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                Energy Pool</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                <span class="text-success text-sm font-weight-bolder"></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-5 col-sm-5 mt-4 mt-md-0">
{{--                    <a data-bs-toggle="modal"--}}
{{--                       data-bs-target="#communication">--}}
                    <a onclick="showModal('{{ asset('assets/video/Intro to Communication Style.mp4') }}')">
                        <div class="card"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                Communication Styles</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a onclick="showModal('{{ asset('assets/video/Perception of Life Intro.mp4') }}')">
                        <div class="card mt-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                Perception of Life</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                <span class="text-success text-sm font-weight-bolder"></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <button data-bs-toggle="modal"
            data-bs-target="#life" hidden>
        modal button
    </button>

    {{--   Introduction Models   --}}
{{--    <div class="modal fade" id="introduction" tabindex="-1" role="dialog" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body" style="background-color: #0f1535;  border-radius: 9px">--}}
{{--                    <video id="introduction-video"  controls>--}}
{{--                        <source src="{{ asset('assets/video/HumanOp ULT Results Intro - Lisa Nelson.mp4') }}" type="video/mp4">--}}
{{--                        Your browser does not support the video tag.--}}
{{--                    </video>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    --}}{{--   Trait Models   --}}
{{--    <div class="modal fade" id="trait" tabindex="-1" role="dialog" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">--}}
{{--                    <video id="trait-video" controls>--}}
{{--                        <source src="{{ asset('assets/video/Intro to Traits.mp4') }}" type="video/mp4">--}}
{{--                        Your browser does not support the video tag.--}}
{{--                    </video>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    --}}{{--   Cycle of Life Models   --}}
{{--    <div class="modal fade" id="cycle_of_life" tabindex="-1" role="dialog" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">--}}
{{--                    <video id="cycle-of-life-video" controls>--}}
{{--                        <source src="{{ asset('assets/video/Intro to The Cycle of Life.mp4') }}" type="video/mp4">--}}
{{--                        Your browser does not support the video tag.--}}
{{--                    </video>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    --}}{{--   Motivational Drivers Models   --}}
{{--    <div class="modal fade" id="motivation" tabindex="-1" role="dialog" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">--}}
{{--                    <video id="motivation-video" controls>--}}
{{--                        <source src="{{ asset('assets/video/Intro to Motivation (Drivers).mp4') }}" type="video/mp4">--}}
{{--                        Your browser does not support the video tag.--}}
{{--                    </video>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    --}}{{--   Alchemic Boundaries Models   --}}
{{--    <div class="modal fade" id="alchemy" tabindex="-1" role="dialog" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">--}}
{{--                    <video id="alchemy-video" controls>--}}
{{--                        <source src="{{ asset('assets/video/Intro to Alchemy.mp4') }}" type="video/mp4">--}}
{{--                        Your browser does not support the video tag.--}}
{{--                    </video>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    --}}{{--   Communication Styles Models   --}}
{{--    <div class="modal fade" id="communication" tabindex="-1" role="dialog" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">--}}
{{--                    <video id="communication-video" controls>--}}
{{--                        <source src="{{ asset('assets/video/Intro to Communication Style.mp4') }}" type="video/mp4">--}}
{{--                        Your browser does not support the video tag.--}}
{{--                    </video>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    --}}{{--   Energy Pool Models   --}}
{{--    <div class="modal fade" id="energy" tabindex="-1" role="dialog" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">--}}
{{--                    <video id="energy-video" controls>--}}
{{--                        <source src="{{ asset('assets/video/Intro to Energy Pool.mp4') }}" type="video/mp4">--}}
{{--                        Your browser does not support the video tag.--}}
{{--                    </video>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    {{--   Perception of Life Models   --}}
    <div class="modal fade" id="life" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
{{--            <div class="modal-header">--}}
{{--            </div>--}}
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <a type="button" class="close float-end text-white" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                    <br>
{{--                    <video id="life-video" controls>--}}
{{--                        <source src="{{ asset('assets/video/Perception of Life Intro.mp4') }}" type="video/mp4">--}}
{{--                        Your browser does not support the video tag.--}}
{{--                    </video>--}}

                    <div class="container show-controls" id="container_video">
                        <div class="wrapper">
                            <div class="video-timeline">
                                <div class="progress-area">
                                    <span id="progree-area-span">00:00</span>
                                    <div class="progress-bar" style="color: #f2661c;"></div>
                                </div>
                            </div>
                            <ul class="video-controls">
                                <li class="options left">
                                    <button class="volume"><i class="fa-solid fa-volume-high" style="color: rgb(242, 102, 28)"></i></button>
                                    <input type="range" min="0" max="1" step="any">
                                    <div class="video-timer">
                                        <span class="current-time" style="color: #f2661c;">00:00</span>
                                        <span class="separator" style="color: #f2661c;"> / </span>
                                        <span class="video-duration" style="color: #f2661c;">00:00</span>
                                    </div>
                                </li>
                                <li class="options center">
                                    <button class="skip-backward"><i class="fas fa-backward" style="color: #f2661c;"></i></button>
                                    <button class="play-pause"><i class="fas fa-play" style="color: #f2661c;"></i></button>
                                    <button class="skip-forward"><i class="fas fa-forward" style="color: #f2661c;"></i></button>
                                </li>
                                <li class="options right">
{{--                                    <div class="playback-content">--}}
{{--                                        <button class="playback-speed"><span class="material-symbols-rounded">slow_motion_video</span></button>--}}
{{--                                        <ul class="speed-options">--}}
{{--                                            <li data-speed="2">2x</li>--}}
{{--                                            <li data-speed="1.5">1.5x</li>--}}
{{--                                            <li data-speed="1" class="active">Normal</li>--}}
{{--                                            <li data-speed="0.75">0.75x</li>--}}
{{--                                            <li data-speed="0.5">0.5x</li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
                                    <button class="fullscreen"><i class="fa-solid fa-expand" style="color: #f2661c;"></i></button>
                                </li>
                            </ul>
                        </div>
                        <video id="video"></video>
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
        <div class="col-12">
            <div id="globe" class="position-absolute end-0 top-10 mt-sm-3 mt-7 me-lg-7">
                <canvas width="700" height="600" class="w-lg-100 h-lg-100 w-75 h-75 me-lg-0 me-n10 mt-lg-5"></canvas>
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
        // document.addEventListener('DOMContentLoaded', function () {
        //     var modals = [
        //         {id: 'introduction', videoId: 'introduction-video'},
        //         {id: 'trait', videoId: 'trait-video'},
        //         {id: 'cycle_of_life', videoId: 'cycle-of-life-video'},
        //         {id: 'motivation', videoId: 'motivation-video'},
        //         {id: 'alchemy', videoId: 'alchemy-video'},
        //         {id: 'communication', videoId: 'communication-video'},
        //         {id: 'energy', videoId: 'energy-video'},
        //         {id: 'life', videoId: 'life-video'}
        //     ];
        //
        //     modals.forEach(function(modal) {
        //         var modalElement = document.getElementById(modal.id);
        //         var videoElement = document.getElementById(modal.videoId);
        //
        //         modalElement.addEventListener('hide.bs.modal', function () {
        //             if (videoElement) {
        //                 videoElement.pause();
        //             }
        //         });
        //     });
        // });

        function showModal(src){

            $('#life').modal('show');

            // $('#video').attr('src', "https://vaibhav1663.github.io/Youtube-Ambient-Mode/demo-video.mp4");
            $('#video').attr('src', src);

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
            speedBtn = container.querySelector(".playback-speed span"),
            speedOptions = container.querySelector(".speed-options"),
            fullScreenBtn = container.querySelector(".fullscreen i");
        let timer;

        const hideControls = () => {
            if(mainVideo.paused) return;
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

            if(hours == 0) {
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
            if(!volumeBtn.classList.contains("fa-volume-high")) {
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
            if(e.target.value == 0) {
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
            if(document.fullscreenElement) {
                fullScreenBtn.classList.replace("fa-compress", "fa-expand");
                return document.exitFullscreen();
            }
            fullScreenBtn.classList.replace("fa-expand", "fa-compress");
            container.requestFullscreen();
        });

        // speedBtn.addEventListener("click", () => speedOptions.classList.toggle("show"));
        skipBackward.addEventListener("click", () => {mainVideo.currentTime -= 5 ;});
        skipForward.addEventListener("click", () => {mainVideo.currentTime += 5});
        mainVideo.addEventListener("play", () => playPauseBtn.classList.replace("fa-play", "fa-pause"));
        mainVideo.addEventListener("pause", () => playPauseBtn.classList.replace("fa-pause", "fa-play"));
        playPauseBtn.addEventListener("click", () => {mainVideo.paused ? mainVideo.play() : mainVideo.pause()});
        videoTimeline.addEventListener("mousedown", () => videoTimeline.addEventListener("mousemove", draggableProgressBar));
        document.addEventListener("mouseup", () => videoTimeline.removeEventListener("mousemove", draggableProgressBar));

    </script>

    <script>
        $('.close').click(function(){
            $('#video').each(function(){ $(this).get(0).pause();
            })
        });
    </script>
@endpush
