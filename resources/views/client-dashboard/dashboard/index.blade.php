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
                            <img src="{{ Auth::user()['photo_url']['url'] ?? URL::asset('assets/img/default-user-image.png') }}"
                                height="80" alt="profile_image" class="w-100 border-radius-lg shadow-sm  ">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="h-100">
                            <a href="{{route('setting')}}">
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

                <div class="d-flex">
                    <div class="nav nav-pills  nav-fill bg-transparent position-static   user-pannel-btn   "
                        role="tablist">
                        <div class="nav-item">
                            <button style="padding: 2px 16px 2px 16px; border-radius: 7px;background-color: #f2661c"
                                class="text-white btn btn-sm-1 btn-md-3 btn-lg-5 ">Access Your<br> Results
                            </button>
                        </div>

                        <div class="nav-item">
                            <button style="padding: 2px 16px 2px 16px; border-radius: 7px;background-color: #f2661c"
                                class=" ms-2 text-white btn btn-sm-2 btn-md-3 btn-lg-5 ">Get Free Pro<br> Access!
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if($admin_answer && !empty($admin_answer['question']))
        <div class="container-fluid p-2 mt-2">

            <div class="d-flex justify-content-between flex-row card card-body text-white gap-5">
                <div class="" style="width: fit-content;">
                    <div>
                        <span style="color: #f2661c;font-size: 26px;font-weight: 800;display: flex;">
                            Your Query : {{$admin_answer['question']['query'] ?? null}}
                        </span>
                    </div>
                    <div>
                        <div class="text-white mt-2">
                            <span> Answer : </span>
                            {{$admin_answer->answer}}
                        </div>
                    </div>
                </div>
                <div class="d-none d-lg-flex flex-column">
                    <div class="nav nav-pills z-index-1 nav-fill bg-transparent position-static pb-5 user-pannel-btn   "
                        role="tablist">
                        <div class="nav-item">
                            <button style="padding: 2px 16px 2px 16px; border-radius: 7px;background-color: #f2661c"
                                class="text-white btn btn-sm-1 btn-md-3 btn-lg-5 ">Access Your<br> Results
                            </button>
                        </div>

                        <div class="nav-item">
                            <button style="padding: 2px 16px 2px 16px; border-radius: 7px;background-color: #f2661c"
                                class=" ms-2 text-white btn btn-sm-2 btn-md-3 btn-lg-5 ">Get Free Pro<br> Access!
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
                        <div class="card" style="height: 375px;">
                            <div class="card-body p-3">
                                <h5 class="text-white">Daily Tip</h5>
                                {{-- <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192);">
                                    --}}
                                    {{-- {{ $tip ? $tip['title'] : '' }}--}}
                                    {{-- </p>--}}
                                <div class="description-container" style="max-height: 300px;">
                                    <p class="text-sm mt-3" style="color: rgb(160, 174, 192);">
                                        @if($tip && !empty($tip['description']))

                                            @if(strlen($tip['description']) > 265)

                                                {!! substr($tip['description'], 0, 260)!!}

                                                &nbsp;&nbsp;
                                                <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#dailyTipModal" style="color: #f2661c;">read
                                                    more...
                                                </a>

                                            @else

                                                {!! $tip['description'] !!}

                                            @endif

                                        @else

                                        @endif
                                    </p>
                                </div>
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
                        <div class="card" style="height: 500px;" style="border-radius: 3rem !important;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)">
                                        CORE STATS</p>
                                    <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)">
                                        Interval of Life: (<span class="text-bold text-sm"
                                            style="color: #f2661c">{{$user_age}}</span>)</p>
                                </div>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Top 3 Traits:</p>
                                <div class="d-flex" style="margin-top: -10px">
                                    @if($topThreeStyles)
                                        @foreach($topThreeStyles as $index => $style)
                                            <p class="fw-bold" style="color: #f2661c">
                                                ({{ $style }}) {{ $index }}@if(!$loop->last),@endif
                                            </p>
                                        @endforeach
                                    @endif
                                </div>
                                <p class="text-sm" style="color: rgb(160, 174, 192)"> Motivational Drivers:</p>
                                <div class="d-flex" style="margin-top: -10px">
                                    @if($topTwoFeatures)
                                        @foreach($topTwoFeatures as $index => $feature)
                                            <p class="fw-bold" style="color: #f2661c">
                                                ({{ $feature }}) {{ $index }}@if(!$loop->last),@endif
                                            </p>
                                        @endforeach
                                    @endif
                                </div>
                                <p class="text-sm" style="color: rgb(160, 174, 192)"> Tolerance Boundaries:</p>
                                @if($boundary)
                                    <p class="fw-bold" style="color: #f2661c; margin-top: -10px">
                                        ({{ $boundary['code_number'] ?? '' }}) {{ $boundary['public_name'] ?? '' }}
                                    </p>
                                @endif
                                <p class="text-sm" style="color: rgb(160, 174, 192)"> Communication Styles:</p>
                                <div class="d-flex" style="margin-top: -10px">
                                    @if($topCommunication)
                                        @foreach($topCommunication as $communication)
                                            <p class="fw-bold" style="color: #f2661c">
                                                {{ $communication }}@if(!$loop->last),@endif
                                            </p>
                                        @endforeach
                                    @endif
                                </div>
                                <p class="text-sm" style="color: rgb(160, 174, 192)"> Perception of Life:</p>
                                <p class="fw-bold" style="color: #f2661c; margin-top: -10px">
                                    {{ $preception == 40 ? "Negative" : ($preception == 41 ? "Neutral" : ($preception == 42 ? "Positive" : '')) }}
                                </p>
                                <p class="text-sm" style="color: rgb(160, 174, 192)">Energy Pool:</p>
                                @if($energyPool)
                                    <p class="fw-bold" style="color: #f2661c; margin-top: -10px">
                                        {{ $energyPool }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                        <div class="card" style="height: 420px;">
                            <div class="card-body p-3">
                                <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> HELP
                                    I'M
                                    HAVING A CHALLENGE</p>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> [CLICK TO ACCESS H. A. I.
                                    SELF-OPTIMIZATION TROUBLESHOOTING INTERFACE]</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-lg-4 mt-2 col-lg-4 col-sm-12 col-md-12  features-card ">
                    <div class="col-lg-12  col-md-12 col-sm-12 mb-4 d-flex flex-column">
                        <div class="card" style="height: 375px;">
                            <div class="card-body p-3">
                                <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> YOUR
                                    OPTIMIZATION STRATEGIES FOR THE NEXT 90 DAYS</p>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Summary:</p>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - More daily activities
                                    that
                                    support your motivational drivers</p>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Create more alone time
                                    Role</p>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Structure your days
                                    based
                                    on
                                    your traits</p>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Incorporate more daily
                                    Movement</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12  col-md-12 col-sm-12 mb-4 d-flex flex-column">

                        <div class="card" style="height: 550px;border-radius: 1rem !important;">
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
                                                            src="{{$podcast->embedded_url}}" frameborder="0"></iframe>
                                                        <h5 class="font-weight-bolder mb-0">
                                                            <span class="text-success text-sm font-weight-bolder"></span>
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
                <div class="row mt-lg-4 mt-2">
                    <div class="fixed-plugin">
                        <textarea rows="3" cols="3" style="background-color: #0f1534;" name="text"
                            class="form-control text-white messageChat mb-2"
                            placeholder="Type your message here..."></textarea>
                        <a style="background-color: #f2661c; color: white; border-radius: 5px !important;"
                            class="btn col-12 fixed-plugin-button haiChatBtn">H.A.I CHAT INTERFACE</a>

                        <!-- H.A.I CHAT INTERFACE -->
                        <div class="card shadow-lg blur m-0 px-0 "
                            style="background-color: #0f1534 !important;z-index: 1111111">
                            <div class="card-header py-1" style="background-color: #f2661c">
                                <div class="d-flex align-items-center justify-content-between">
                                    <!-- <div class="col-2 px-0 mb-2">
                                    <div class="float-start d-flex flex-column">
                                        <img src="{{asset('assets/img/team-3.jpg')}}" alt="Avatar" class="avatar">
                                        <div class="header-info text-white">
                                            <div class=" fs-13px">Need help?</div>
                                            <div class=" fs-7px">We reply immediately</div>
                                        </div>
                                    </div>
                                </div> -->

                                    <div class="  d-flex  ">

                                    </div>
                                    <div class="  d-flex align-items-center  ">
                                        <h5 class="text-center text-white fs-15px">H.A.I CHAT INTERFACE</h5>
                                    </div>

                                    <div class="d-flex align-items-center ">
                                        <div class="float-end mt-4">
                                            <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                                                <i class="fa fa-close"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- sidebar of chatbot -->
                            <div class="d-flex">
                                @livewire('client.chat.index')
                            </div>
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
                        @livewire('client.client-query.client-query')
                    </div>
                </div>
            </div>
        </div>

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
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <p>{!! $tip['description'] ?? null !!}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <input type="checkbox"
                                        style=" margin: 4px 0 0; line-height: normal; width: 20px; height: 20px;"
                                        onchange="onDailyTipAllRead(this)" id="daily-tip-checkbox" {{$tip['is_read'] ? "disabled checked" : ""}}>
                                    <label>Have you read it all ?</label>
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
        var addPoint = `{{Session::has('add_point') ? '+'.Session::pull('add_point') : '' }}`;

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
            $('#exampleModalMessage').modal('hide');
            $('#add_feedback').click();
        })

        document.querySelector('.haiChatBtn').addEventListener('click', function () {
            document.querySelector('.chatBoxShow').classList.remove('d-block');
            document.querySelector('.chatBoxShow').classList.add('d-none');
            document.querySelector('.chatBoxx').classList.remove('d-none');
            document.querySelector('.chatBoxx').classList.add('d-block');
        });

        function onDailyTipAllRead(e) {

            if (e.checked) {

                $('#daily-tip-checkbox').attr('disabled', true);


                $.ajax({
                    url: '{{ route("read-daily-tip") }}',
                    method: 'POST',
                    data: [],
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    success: function (response) {
                        if(response.result.data.point > 0 ){
                            animateNumber('+'+response.result.data.point);

                            old_count = $('#coin-count').text();
                            console.log(response.result.data.point,old_count);
                            $('#coin-count').text(parseInt(response.result.data.point) + parseInt(old_count));
                        }
                    },
                    error: function (response) {

                    }
                });

            }
        }
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
@endpush
