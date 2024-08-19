@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])
<style>
    .description-container::-webkit-scrollbar {
        width: 10px; /* Width of the scrollbar */
    }

    .description-container::-webkit-scrollbar-track {
        background: rgb(160, 174, 192); /* Color of the track */
    }

    .description-container::-webkit-scrollbar-thumb {
        background-color: #888; /* Color of the handle */
        border-radius: 10px; /* Roundness of the handle */
        /*border: 2px solid #555; !* Space around the handle *!*/
    }

    /* Custom scrollbar for Firefox */
    .description-container {
        scrollbar-width: thin; /* Thickness of the scrollbar */
        scrollbar-color: #888 rgb(160, 174, 192); /* Color of the scrollbar and track */
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
                        <p class="text-white text-sm col-12">Perceptive Trait (Thinking) For Strategy and Problem
                            Solving
                            Activities</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6  ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1">
                                    <button
                                        style="padding: 2px 16px 2px 16px; border-radius: 7px;background-color: #f2661c"
                                        class="text-white btn btn-lg">Access Your<br> Results
                                    </button>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 ">
                                    <button
                                        style="padding: 2px 16px 2px 16px; border-radius: 7px;background-color: #f2661c"
                                        class=" ms-2 text-white btn btn-lg">Get Free Pro<br> Access!
                                    </button>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <section class="py-3">
            <div class="row">
                <div class="mt-lg-4 mt-2 col-3">
                    <div class="col-lg-12 col-md-6 mb-4">
                        <div class="card" style="height: 375px;">
                            <div class="card-body p-3">
                                <h5 class="text-white">Daily Tip</h5>
                                <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192);">
                                    {{ $tip ? $tip['title'] : '' }}
                                </p>
                                <div class="description-container" style="max-height: 200px; overflow-y: auto;">
                                    <p class="text-sm mt-3" style="color: rgb(160, 174, 192);">
                                        {{ $tip ? $tip['description'] : '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-6 mb-4">
                        <div class="card" style="height: 375px;">
                            <div class="card-body p-3">
                                <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> LIBRARY
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
                <div class="mt-lg-4 mt-2 col-6">
                    <div class="col-lg-12 col-md-6 mb-4">
                        <div class="card" style="height: 575px;border-radius: 1rem !important;">
                            <div class="card-body p-3">
                                <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> HIP -
                                    HumanOp Integration Podcast</p>
                                <div class="card mb-4"
                                     style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="">
                                                <div class="numbers mt-3">
                                                    <iframe class="col-lg-12 col-md-12" style="height: 430px"
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
                    <div class="col-lg-12 col-md-6 mb-4">
                        <div class="card" style="height: 175px;">
                            <div class="card-body p-3">
                                <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> HELP I'M
                                    HAVING A CHALLENGE</p>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> [CLICK TO ACCESS H. A. I.
                                    SELF-OPTIMIZATION TROUBLESHOOTING INTERFACE]</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-lg-4 mt-2 col-3">
                    <div class="col-lg-12 col-md-6 mb-4">
                        <div class="card" style="height: 375px;">
                            <div class="card-body p-3">
                                <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> YOUR
                                    OPTIMIZATION STRATEGIES FOR THE NEXT 90 DAYS</p>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Summary:</p>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - More daily activities that
                                    support your motivational drivers</p>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Create more alone time
                                    Role</p>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Structure your days based
                                    on
                                    your traits</p>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Incorporate more daily
                                    Movement</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-6 mb-4">
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
                </div>
            </div>
            <div class="row mt-lg-4 mt-2">
                <div class="fixed-plugin">
                <textarea rows="3" cols="3" style="background-color: #0f1534;" name="text"
                          class="form-control text-white messageChat mb-2"
                          placeholder="Type your message here..."></textarea>
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
                                        </div>
                                        <div class="mt-4">
                                            <h5 class="text-white text-bold">Previous 30 Days chat</h5>
                                            <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                                Merge Videos with FFmpeg</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @livewire('client.chat.index')
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2nd dislike question Modal -->
        <div class="modal fade" id="exampleModalMessage" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalMessage" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fs-4 text-white">Describe your question briefly to
                                        us</label>
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <form>
                                        <div class="form-group mt-4">
                                <textarea rows="4" class="form-control text-white" style="background-color: #0f1535" id="message-text"
                                          placeholder="Type your question here..."></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Send
                            </button>
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
    </script>
@endpush
