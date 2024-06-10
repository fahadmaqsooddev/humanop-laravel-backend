@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])
@section('content')
    <div class="container-fluid">
        <div class="page-header min-height-100 border-radius-xl">
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ URL::asset('assets/img/bruce-mars.jpg') }}" alt="profile_image"
                             class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1 text-white">
                            {{$user['first_name']}}  {{$user['last_name']}}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm text-white">
                            Optimal Trait To Be In Right Now:
                        </p>
                        <p class="text-white text-sm col-12">Perceptive Trait (Thinking) For Strategy and Problem Solving
                            Activities</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6  ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1">
                                    <button class="ms-1 text-white bg-gradient-primary rounded-3">Access Your<br> Results</button>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 ">
                                    <button class="ms-1 text-white bg-gradient-primary rounded-3">Get Free Pro<br> Access!</button>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <section class="py-3">
            <div class="row mt-lg-4 mt-2">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card" style="height: 375px;">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> DAILY
                                TIP</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Remember that between the
                                hours<br> of 2 - 4pm everyday you should schedule a power nap as an energy management
                                strategy!</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="card" style="height: 375px;" style="border-radius: 3rem !important;">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> CORE
                                STATS</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Top 3 Traits:</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Motivational Drivers:</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Tolerance Boundaries:</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Communication Styles</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Perception of Life:</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card" style="height: 375px;">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> YOUR
                                OPTIMIZATION STRATEGIES FOR THE NEXT 90 DAYS</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Summary:</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - More daily activities that
                                support your motivational drivers</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Create more alone time
                                Role</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Structure your days based on
                                your traits</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Incorporate more daily
                                Movement</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-lg-4 mt-2">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card" style="height: 375px;">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> LIBRARY OF
                                RESOURCES & TRAININGS</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> New Updatesl</p>
                            <br>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - More Conflit Resolution
                                Strategies</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Optimizing Your Space Training
                                Added</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Updated Dreivers Trainings</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="card" style="height: 375px;" style="border-radius: 3rem !important;">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> HIP -
                                HumanOp Integration Podcast</p>
                            <div class="card mb-4"
                                 style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="">
                                            <div class="numbers mt-3">
                                                <iframe class="col-lg-12 col-md-12" height="220"
                                                        src="https://app.hiro.fm/embed/65c95c9f355f13001914ccab"
                                                        frameborder="0"></iframe>
                                                <h5 class="font-weight-bolder mb-0">
                                                    <span class="text-success text-sm font-weight-bolder"></span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card" style="height: 375px;">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> HELP I'M
                                HAVING A CHALLENGE</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> [CLICK TO ACCESS H. A. I.
                                SELF-OPTIMIZATION TROUBLESHOOTING INTERFACE]</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-lg-4 mt-2">
                <div class="fixed-plugin">
                    <a style="background-color: #f2661c; color: white; border-radius: 5px !important;"
                       class="btn col-12 fixed-plugin-button">H.A.I CHAT INTERFACE</a>
                    <div class="card shadow-lg blur" style="background-color: #0f1534 !important;">
                        <div class="card-header pb-0 pt-3" style="background-color: #f2661c">
                            <h5 class="text-center text-white">H.A.I CHAT INTERFACE</h5>
                            <div class="float-start d-flex">
                                <img src="{{asset('assets/img/team-3.jpg')}}" alt="Avatar" class="avatar">
                                <div class="header-info text-white">
                                    <div class="header-title">Need help?</div>
                                    <div class="header-subtitle">We reply immediately</div>
                                </div>
                            </div>
                            <div class="float-end mt-4">
                                <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                                    <i class="fa fa-close"></i>
                                </button>
                            </div>
                            <!-- End Toggle Button -->
                        </div>
                        <div class="d-flex">
                            <div class="col-3">
                                <div class="chatbox">
                                    <div class="chatbox-content" style="background-color: #f2661c">
                                        <div class="mt-4 chat-hover d-flex" style="cursor: pointer;">
                                            <i class="fa fa-plus" style="color: white; margin-top: 8px"></i>
                                            <h5 class="text-white text-bold" style="margin-left: 12px">New chat</h5>
                                        </div>
                                        <hr>
                                        <div class="mt-4">
                                            <h5 class="text-white text-bold">Today chat</h5>
                                            <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                                Set Permissions for Directory</p>
                                        </div>
                                        <div class="mt-4">
                                            <h5 class="text-white text-bold">Yesterday chat</h5>
                                            <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                                Permission Denied Error Troubleshooting</p>
                                            <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                                Customizing Embedding Videos</p>
                                            <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                                Bootstrap: Utilizar bordes blancos</p>
                                        </div>
                                        <div class="mt-4">
                                            <h5 class="text-white text-bold">Previous 30 Days chat</h5>
                                            <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                                Merge Videos with FFmpeg</p>
                                            <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                                Permission Denied Error Troubleshooting</p>
                                            <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                                Customizing Embedding Videos</p>
                                            <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                                Bootstrap: Utilizar bordes blancos</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9">
                                <div class="chatbox">
                                    <div class="chatbox-content" id="chatbox-content">
                                        <div style="display: flex; justify-content: flex-start">
                                            <div class="message bot-message">Welcome to our store! Whether you have a
                                                specific
                                                question or need
                                                assistance, we're here for you. What would you like to know? 😊
                                            </div>
                                        </div>
                                        <div style="display: flex; justify-content: flex-end">
                                            <div class="message user-message">Shopping guide</div>
                                        </div>
                                        <div style="display: flex; justify-content: flex-start">
                                            <div class="message bot-message">Welcome to our store! Whether you have a
                                                specific
                                                question or need
                                                assistance, we're here for you. What would you like to know? 😊
                                            </div>
                                        </div>
                                        <div style="display: flex; justify-content: flex-end">
                                            <div class="message user-message">Shopping guide</div>
                                        </div>
                                        <div style="display: flex; justify-content: flex-start">
                                            <div class="message bot-message">Welcome to our store! Whether you have a
                                                specific
                                                question or need
                                                assistance, we're here for you. What would you like to know? 😊
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chatbox-input">
                                        <input type="text" id="user-input" placeholder="Type your message here...">
                                        <button id="send-button">&#9658;</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
{{--        @include('client-dashboard.dashboard.resources.views.layouts.footers.auth.footer')--}}
    </div>
@endsection
