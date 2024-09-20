@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])
@section('content')
    <div class="row">
        <div class="col-lg-12 position-relative z-index-2">
            <div class="container-fluid px-0 px-md-5">
                <section>
                    <div class="row mt-lg-4 mt-2">
                        <div class="col-12">
                            <div class="card px-0" style="text-align: center">
                                <div class="card-body p-3 ">
                                    <h1 class="text-white">Your HumanOp Profile Overview</h1>
                                    <video id="myVideo100" class="slider-padding mb-5 videoStop mt-5" width="1100"
                                           height="400" controls>
{{--                                        <source--}}
{{--                                            src="{{asset('assets/video/HumanOp ULT Results Intro - Lisa Nelson.mp4')}}"--}}
{{--                                            type="video/mp4" id="video-source">--}}
{{--                                        <source src="mov_bbb.ogg" type="video/ogg">--}}
{{--                                        Your browser does not support HTML video.--}}
                                    </video>
                                    <ul style="justify-content: space-evenly; background-color: transparent"
                                        class="nav nav-pills">
                                        <li><a href="#summaryReport"
                                               class="flex-sm-fill text-lg-center nav-link text-white text-bold active"
                                               data-toggle="tab">Summary Report</a>
                                        </li>
                                        <li><a href="#coreStats" class="flex-sm-fill text-lg-center nav-link text-white"
                                               data-toggle="tab">Core Stats</a>
                                        </li>
                                        <li><a href="#dayPlan" class="flex-sm-fill text-lg-center nav-link text-white"
                                               data-toggle="tab">90 Days Plan</a>
                                        </li>
                                    </ul>
                                    <div class="container tab-content clearfix">
                                        <div class="tab-pane active" id="summaryReport">
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
                                        <div class="tab-pane" id="coreStats">
                                            <div class="slider-padding p-3 mt-5">
                                                <h4 class="primaryColor">Profile Overview</h4>
                                                <p class="mt-4">Your HumanOp profile reveals a unique combination of traits that shape your approach to life and work. Here are the key highlights based on your ULT Summary Report:</p>
                                                <div class="row d-flex mt-5">
                                                    @foreach($topThreeStyles as $index => $style)
                                                    <div class="col-lg-4 col-sm-12 col-md-6">
                                                            <div class="card" style="height: auto">
                                                                <div class="card-body p-3 ">
                                                                    <h5 onclick="showFeatureVideo('{{$style[3]}}')" style="cursor: pointer;" class="text-white fs-10px">{{$index + 1}}. {{$style[1]}}</h5>
                                                                    <div class="description-container">
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
                                                                    <h5 onclick="showFeatureVideo('{{$feature[3]}}')" style="cursor: pointer;" class="text-white fs-10px">{{$index + 1}}. {{$feature[1]}}</h5>
                                                                    <div class="description-container">
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

        showFeatureVideo('{{asset('assets/video/HumanOp ULT Results Intro - Lisa Nelson.mp4')}}');

        function showFeatureVideo(src){

            var video = document.getElementById('myVideo100');
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
            video.play();
            console.log({
                src: source.getAttribute('src'),
                type: source.getAttribute('type'),
            });

            // setTimeout(function() {
            //     video.pause();
            //
            //     source.setAttribute('src', "https://saas.humanoptech.com/assets/video/The%20Effervescent%20Trait.mp4");
            //     source.setAttribute('type', 'video/webm');
            //
            //     video.load();
            //     video.play();
            //     console.log({
            //         src: source.getAttribute('src'),
            //         type: source.getAttribute('type'),
            //     });
            // }, 3000);

            // console.log(src);
            //
            // var video = document.getElementById('myVideo100');
            //
            // $('#video-source').attr('src', src);
        }

    </script>

@endpush

