@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
@section('content')
    <style>
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

        .hidden {
            display: none;
        }

        .read-more-content {
            margin-top: 20px;
        }

        button.play-pause-center {
            color: #1b3a62;
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

        .progress-bar::before,
        .progress-area span {
            display: none;
        }

        .instruction_text {
            color: #1b3a62;
            text-transform: uppercase;
            font-size: 27px;
        }

        #intro_to_cycle_to_life p,
        #intro_to_cycle_to_life h4,
        #intro_to_trait_text p,
        #intro_to_trait_text h4,
        #your_motivation_text p,
        #your_motivation_text h4,
        #your_motivation_text p,
        #your_motivation_text h4,
        #feature_0_text p,
        #feature_0_text h4,
        #your_boundary_text p,
        #your_boundary_text h4,
        #your_communication_text p,
        #your_communication_text h4,
        #energy_pool_text p,
        #energy_pool_text h4,
        #your_perception_text p,
        #your_perception_text h4{
            color: #1b3a62;
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

        .custom-color font {
            color: #1b3a62 !important; /* Replace with your desired color */
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

            .right .pic-in-pic {
                display: none;
            }
        }

        .left-nav-blue-light-color {
            background: #f8d7da !important;
        }

        .orange-border {
            border: 1px solid #1b3a62;
        }

        .heading {
            color: orangered;
        }

        .custom-color {
            color: #0f1535;
            font-weight: bold;
        }


        .text-tiny {
            font-size: 0.5rem !important;
        }

        .text-small {
            font-size: 0.75rem !important;
        }

        .text-default {
            font-size: 1rem !important;
        }

        .text-big {
            font-size: 1.5rem !important;
        }

        .text-huge {
            font-size: 2rem !important;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 position-relative z-index-2">
            <div class="container-fluid px-0 px-md-5 ">
                <section>
                    <div class="row mt-lg-4 mt-2 ">
                        <div class="col-12">
                            <div class="card px-0 left-nav-blue-light-color orange-border" style="text-align: center">
                                {{-- hello --}}
                                <div class="card-body p-3 " style="background-color: white;">
                                    <p id="main_result_intro"></p>
                                    <h1 class="" style="color: #1b3a62">Your HumanOp Profile Overview</h1>
                                    <p class="custom-color">Date of Results: {{$created_at ?? ''}}</p>
                                    <div class="video-container show-controls" id="container_video">
                                        <div class="wrapper mx-auto w-75 ">
                                            <div
                                                class="center-play-pause d-flex align-items-center justify-content-center h-75 w-100   mx-auto">
                                                <button class="btn play-pause-center fs-1"
                                                        style="color: #1b3a62;"><i class="fas fa-play"></i>
                                                </button>
                                            </div>
                                            <div class="video-timeline">
                                                <div class="progress-area">
                                                    <span id="progree-area-span">00:00</span>
                                                    <div class="progress-bar" style="color: #1b3a62;"></div>
                                                </div>
                                            </div>
                                            <ul class="video-controls">
                                                <li class="options left">
                                                    <button class="volume"><i class="fa-solid fa-volume-high"
                                                                              style="color: #1b3a62"></i>
                                                    </button>
                                                    <input type="range" min="0" max="1" step="any">
                                                    <div class="video-timer">
                                                        <span class="current-time" style="color: #1b3a62;">00:00</span>
                                                        <span class="separator" style="color: #1b3a62;"> / </span>
                                                        <span class="video-duration"
                                                              style="color: #1b3a62;">00:00</span>
                                                    </div>
                                                </li>
                                                <li class="options center">
                                                    <button class="skip-backward"><i class="fas fa-backward"
                                                                                     style="color: #1b3a62;"></i>
                                                    </button>
                                                    <button class="play-pause"><i class="fas fa-play"
                                                                                  style="color: #1b3a62;"></i>
                                                    </button>
                                                    <button class="skip-forward"><i class="fas fa-forward"
                                                                                    style="color: #1b3a62;"></i>
                                                    </button>
                                                </li>
                                                <li class="options right">
                                                    <button class="fullscreen"><i class="fa-solid fa-expand"
                                                                                  style="color: #1b3a62;"></i></button>
                                                </li>
                                            </ul>
                                        </div>
                                        <video id="myVideo100" class="w-100 h-100 " style="max-height: 500px;"></video>
                                    </div>

                                    <div class="row d-flex">
                                        <div id="intro_to_cycle_to_life" class="col-12 mt-3"
                                             style="display: none;background-color:#eaf3ff !important;">
                                            <div id="intro_to_cycle_to_life_text" class="card p-2" style="height: auto;text-align: justify;background-color:#eaf3ff !important;">
                                                <h4 class="" style="color: #1b3a62">{{$cycle_life['public_name']}}</h4>
                                                <p style="color: #1b3a62 !important;">{!!$cycle_life['description']!!}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex" style="">
                                        <div id="roadworthy_life_cycle" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="roadworthy_life_cycle_text" class="p-2"
                                                 style="height: auto;color:#1C365E !important;background-color:#eaf3ff !important;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="text-align: justify; font-size:1rem;"><span
                                                        style="color: #1b3a62;font-size:1rem;font-weight:bold;">Cycle of Life - Roadworthy (21-29): </span><br><br>
                                                    Your current interval of life between 21 and 29
                                                    is what we call “The Road Worthy Interval”. Now,
                                                    if you were to consider your body as the vehicle
                                                    that's transporting your thoughts around in this
                                                    life, this particular interval is when your
                                                    “vehicle of self” is what we call officially
                                                    road worthy. In other words, you're fully
                                                    assembled, you're ready to leave the showroom
                                                    floor and you're ready to make an impact on the
                                                    world. So we invite you to take a moment and
                                                    recognize how this resonates with you. A really
                                                    important thing to know about this interval is
                                                    that even though you are physically mature and
                                                    society declares you legally an adult, official
                                                    adulthood doesn't actually take hold until much
                                                    later in life.
                                                </p>
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color:#1C365E !important; text-align: justify; font-size:1rem">
                                                    So if you're not officially an adult, what do
                                                    you rely upon to help you navigate this current
                                                    interval in the years moving forward? Well, the
                                                    fact is no two people are alike. And what this
                                                    means is that no two people operate the same way
                                                    on the road of life. And no one gave our parents
                                                    an operating manual for us when we were born. So
                                                    what you have to rely on in terms of life
                                                    navigation tools are the experiences and
                                                    influences you've had up until this point.
                                                    You're upbringing, your beliefs, your values,
                                                    your attitudes, your education, cultural
                                                    influences. Any religious or spiritual
                                                    influences. And, of course, popular opinion of
                                                    friends and family and colleagues. And it's not
                                                    that these experiences or influences are bad or
                                                    wrong. Some support you and give you energy, and
                                                    it's important to honor the value that these
                                                    dynamics bring to your life. But because
                                                    everyone is so unique, it's equally important to
                                                    recognize what naturally works for another may
                                                    not naturally work for you. Some of your
                                                    experiences and influences you're drawing from
                                                    will take energy from you because they're not in
                                                    alignment with your unique “vehicle of self.”
                                                    All of the experiences and influences you've had
                                                    and are having are important for you because
                                                    they contribute to making you “who” you are. But
                                                    there's another aspect of you that cannot be
                                                    ignored “what” you are. Your HumanOp ULT
                                                    assessment results essentially give you an
                                                    operating manual for “what” you are: an
                                                    objective understanding of how you specifically
                                                    operate your unique “vehicle of self”, so that
                                                    you can access your highest performance
                                                    potential. And the operating manual doesn't rely
                                                    on drawing from your life experiences,
                                                    influences, your opinions of yourself, or the
                                                    opinions of others. It's your very specific
                                                    manual or blueprint. And when you consistently
                                                    access the understanding from it, It can help
                                                    you make accurate intelligent choices about what
                                                    you uniquely need on a daily basis in order to
                                                    thrive in all aspects of your life.
                                                </p>

                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color:#1C365E !important; text-align: justify; font-size:1rem">
                                                    The overall nature of this current age interval
                                                    you're experiencing is that it's a time for
                                                    experimentation with life. So give yourself
                                                    plenty of opportunities to explore and
                                                    experiment, try new things, seek new alignments,
                                                    activate areas of interest. You will sense an
                                                    internal urgency during the interval to find a
                                                    path, and even a partner, and pressure and
                                                    confusion can escalate within you during the
                                                    interval. It's really important to recognize
                                                    that every choice you make has consequences. And
                                                    the really great thing is when you know your
                                                    unique operating manual, you also learn what
                                                    intelligent choices are for you, as you're
                                                    exploring and experimenting…choices that
                                                    actually keep you energized and optimized. And
                                                    when you make these intelligent choices, this is
                                                    where you can fully realize things like health,
                                                    wealth, love, and perfect self expression.
                                                    Making healthy choices in this interval sets you
                                                    up for success when you enter your next interval
                                                    at age 30, which is the most powerful interval
                                                    of all the intervals of life,
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div id="power_life_cycle" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="power_life_cycle_text" class="p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color:#1C365E !important; text-align: justify; font-size:1rem;background-color:#eaf3ff !important;"><span
                                                        style="color: #1b3a62;font-size:1rem;font-weight:bold;text-align:justify;">Cycle of Life - The Power Interval (30-33):</span><br><br>
                                                    Your current interval of life between 30 and 33
                                                    is “The Power Interval”. This is a time of life
                                                    when you have the most physical energy in all of
                                                    life, and when you begin to make your mark on
                                                    society. You're naturally marketable and
                                                    negotiable in this interval. Employers are very
                                                    attracted to hiring people in this interval
                                                    because there is an undeniable natural vitality
                                                    that exudes from you (particularly if you've
                                                    made intelligent choices for yourself in the
                                                    previous interval). This vitality actually
                                                    serves as a catapult of sorts for you to embrace
                                                    clarity and confidence around your potential,
                                                    and an internal recognition that you are more
                                                    viable than you ever thought. And this
                                                    recognition of viability is empowering. You do
                                                    have some life experience at this point in your
                                                    unfoldment and you think you're somewhat
                                                    immortal. This is the interval where marriages,
                                                    kids and building careers gain traction. The
                                                    things you want to be aware of and manage for
                                                    yourself in this interval is that it's often
                                                    more difficult to say no during this time as you
                                                    feel somewhat invincible. And we recommend that
                                                    you're checking with yourself frequently in this
                                                    interval to make sure you're not signing up for
                                                    too much or perhaps even letting the empowerment
                                                    go to your head by displaying big shot itis.
                                                    Overall, making intelligent choices for yourself
                                                    in this interval is setting you up for success
                                                    as you enter the most transformational interval
                                                    of your life beginning at age 34.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div id="mid_life_life_cycle" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="mid_life_life_cycle_text" class="p-2"
                                                 style="height: auto;background-color:#eaf3ff !important;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color:#1C365E !important; text-align: justify; font-size:1rem"><span
                                                        style="color: #1b3a62;font-size:1rem;font-weight:bold;">Cycle of Life - Mid-Life Transformation (34-42):</span><br><br>
                                                    Your current interval of life starts at 34 and
                                                    ends at 43, and it is the most transformational
                                                    aspect of anyone's life. It's called the
                                                    “Midlife Transformation”, and no one is exempt.
                                                    It's very much like a rite of passage. This time
                                                    of life compresses you, and it pushes you
                                                    outside of your comfort zone. And it's very
                                                    similar to the metamorphosis the caterpillar
                                                    experiences on its way to becoming a butterfly.
                                                    Right at age 34, a thin veil slowly begins to
                                                    wrap around you. And depending upon where you
                                                    are in this interval you can actually start to
                                                    feel pretty claustrophobic at times. You may
                                                    even have internal feelings of wanting to throw
                                                    it all away.
                                                </p>
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color:#1C365E !important; text-align: justify; font-size:1rem">
                                                    So many transformative life events occur during
                                                    the interval, and it's not about categorizing
                                                    them as bad or good. They are simply
                                                    transformative: marriages, divorce, births,
                                                    deaths; addictions can manifest or even find
                                                    healing during this time; new jobs, or losing a
                                                    job; or moving across the country or moving to
                                                    another country; promotions and many many other
                                                    events occur during this time. Now it doesn't
                                                    mean that these types of events don't occur in
                                                    other intervals of life, they are just more
                                                    concentrated in this interval. In other words,
                                                    there's a lot happening all at once and the
                                                    demands can often feel overwhelming. And it can
                                                    have you at times feeling slightly or even
                                                    greatly dissatisfied with your current state of
                                                    existence. Tension can often accompany your
                                                    daily thoughts and discontentment can manifest.
                                                    And as you hear me describing this interval, you
                                                    may resonate with many of these dynamics. The
                                                    grass can start to look greener on the other
                                                    side and current plans and aspirations can take
                                                    on a more lackluster appeal. At age 39, which is
                                                    that gold tick mark that you see on the Cycle of
                                                    Life here, a shift occurs. You actually start to
                                                    go through some losses, and this marks the
                                                    beginning of leaving the old behind as we once
                                                    knew it. We often say at 39, one is experiencing
                                                    being in the eye of the hurricane or the middle
                                                    of the cocoon. There's a calmness in that eye.
                                                    You can see the light at the end of the tunnel,
                                                    and you can actually begin to hear what's
                                                    authentically driving you more clearly. This is
                                                    the time in this transformational interval where
                                                    decisions and preparations are made and starting
                                                    to get comfortable with them. It's very slow
                                                    moving. The eye is quiet, but you do begin to
                                                    see where you need to go. Perhaps some of you
                                                    are in that right now. In other words, the mind
                                                    quiets enough so that you can begin to embrace
                                                    the natural transformation that is occurring for
                                                    you. This metamorphosis that occurs in all of
                                                    nature is the transformation from an immature
                                                    form to an adult form. No action is needed on
                                                    your part other than to take this time to stay
                                                    calm, stay clear and stay true to yourself.
                                                    Whatever struggles you are experiencing, remind
                                                    yourself that this too shall pass… and it will.
                                                    Remind yourself not to throw it all away. And if
                                                    you hold on for the rest of the ride, you will
                                                    weather the storm and you will emerge at 43
                                                    officially an adult with an entirely new outlook
                                                    on life. Remember to refer to your unique
                                                    HumanOp operating manual throughout this
                                                    interval to make sure that you remain energized
                                                    and optimized as you experience this
                                                    transformative time of life.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div id="awareness_life_cycle" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="awareness_life_cycle_text" class="p-2"
                                                 style="height: auto;background-color:#eaf3ff !important;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color:#1C365E !important; text-align: justify; font-size:1rem;background-color:#eaf3ff !important;"><span
                                                        style="color: #1b3a62;font-size:1rem;font-weight:bold;">Cycle of Life - Awareness (43-51):</span><br><br>
                                                    Your current interval begins at age 43 and it
                                                    ends at 52. This interval is called “The
                                                    Interval of Awareness”, and it marks the
                                                    beginning of adulthood. It's interesting because
                                                    even though society embraces a 21 year old as an
                                                    adult, by nature, age 21 simply highlights a
                                                    physically mature individual. Adulthood is
                                                    something that requires a history of experience
                                                    in making life choices. So consider
                                                    this…throughout the past ten years you have
                                                    experienced the metamorphosis of what's called
                                                    the midlife transformation. And you have weather
                                                    the storm, and you have emerged as an adult.
                                                    It's not until age 43 that one is officially an
                                                    adult by nature. You are now awake, and you have
                                                    an innate knowingness that a completely
                                                    different life lies ahead for you. An
                                                    opportunity to pursue a whole new outlook on
                                                    life presents itself to you during this
                                                    interval. And your perception of reality,
                                                    purpose and love holds an even greater
                                                    significance. Reason ability and a recognition
                                                    of your mortality becomes the focus of this
                                                    interval. It's really the first wake up call for
                                                    you that life is short. Depending on how deep
                                                    into this interval you are, notice for yourself,
                                                    this new, more awareness filled desire, to
                                                    gather more information, indulge in meaningful
                                                    experiences, and even take better care of your
                                                    body. The awareness that you have this one body,
                                                    and in order to sustain it, you have to maintain
                                                    it. All of these dynamics take priority for you
                                                    in this interval. It's important to recognize
                                                    that you are essentially reinventing yourself in
                                                    this interval, and this is great. You simply
                                                    want to use your awareness to ensure that you're
                                                    not getting too distracted with the reinvention
                                                    process. The workshop junkie syndrome comes into
                                                    play for some people during this interval. And,
                                                    of course, there's nothing wrong with gathering
                                                    more information or aligning with meaningful
                                                    experiences, just remind yourself to notice what
                                                    really resonates for you and what's just adding
                                                    more to your life. And really enjoy this
                                                    interval! It's like a rest vacation after the
                                                    more tumultuous dynamic of the previous interval
                                                    that you just experienced.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div id="forward_life_cycle" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="forward_life_cycle_text" class="p-2"
                                                 style="height: auto;background-color:#eaf3ff !important;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color:#1C365E !important; text-align: justify; font-size:1rem;background-color:#eaf3ff !important;"><span
                                                        style="color: #1b3a62;font-size:1rem;font-weight:bold;">Cycle of Life - Pay It Forward (52-65):</span><br><br>
                                                    Your current interval begins at age 52 and it
                                                    ends at 66. This interval is called “The Pay It
                                                    Forward Interval”. All that you've collected
                                                    over the past many years becomes fuel for the
                                                    gifts you are to leave behind in this life as
                                                    you pay it forward. You've become more
                                                    altruistic. You're done competing. You no longer
                                                    are territorial and you're well versed in your
                                                    views and your expertise. It becomes “beyond
                                                    you”. You become aware of your purpose and want
                                                    to give back and contribute to causes that are
                                                    important to you. With this interval comes
                                                    personal forgiveness and understanding. You
                                                    become comfortable in your own skin and you feel
                                                    worthy of your age and the respect that it
                                                    commands. There's actually a proud survival that
                                                    shines through in your smile. You do want to
                                                    make sure that you continue to pay attention to
                                                    your physical health in this interval, because
                                                    just like a vehicle, the longer it's driven, it
                                                    continues to rack up more miles. So you're
                                                    continuing to rack up more miles on your
                                                    “vehicle of self”. And as you pay it forward,
                                                    also pay attention to self care to ensure that
                                                    you're remaining energized and optimized.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div id="liberated_life_cycle" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="liberated_life_cycle_text" class="p-2"
                                                 style="height: auto;background-color:#eaf3ff !important;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color:#1C365E !important; text-align: justify; font-size:1rem;background-color:#eaf3ff !important;"><span
                                                        style="color: #1b3a62;font-size:1rem;font-weight:bold;">Cycle of Life - Liberated (66-69): </span><br><br>
                                                    Your current interval begins at age 66 and ends
                                                    at 70, and even for some, it can go on up to 75.
                                                    It's the interval where you experience
                                                    “Liberation”...a freedom from responsibility in
                                                    this life. Life becomes more manageable in this
                                                    interval. Even though it's retirement age, many
                                                    choose to keep working during this interval.
                                                    It's how you work that will shift. The dynamics
                                                    become more relaxed. There's an urgency to
                                                    release unnecessary burdens..working less hours
                                                    and offering yourself time to try something new
                                                    and fun. You may choose to travel, or sing or
                                                    dance, or join an organization, or take up a new
                                                    hobby or sport. The internal freedom that's
                                                    activated within you in this interval provides
                                                    you with an innate desire to celebrate life. You
                                                    do want to pay attention and respect your
                                                    bandwidth in this interval… and keep taking care
                                                    of your physical body to ensure that you remain
                                                    energized and optimized.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div id="being_life_cycle" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="being_life_cycle_text" class="p-2"
                                                 style="height: auto;background-color:#eaf3ff !important;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color:#1C365E !important; text-align: justify; font-size:1rem;background-color:#eaf3ff !important;"><span
                                                        style="color: #1b3a62;font-size:1rem;font-weight:bold;">Cycle of Life - Being (70-74):</span><br><br>
                                                    Your current interval begins at age 70 and ends
                                                    at 75. And we call this interval “The Interval
                                                    of Being”. So much has been archived in the
                                                    files of your physicality by this time and
                                                    nature will actually begin to delete that which
                                                    is most current. And it's important to remind
                                                    yourself that it's okay to discard that which is
                                                    not important, without putting pressure or
                                                    judgment on yourself to retain everything, as
                                                    this is what will extend and improve the quality
                                                    of your life. It's really no different from
                                                    running out of memory on one of your devices.
                                                    Attempting to override or attempting to retain
                                                    more data just puts pressure on your system. And
                                                    you certainly want to avoid crashing. Quite
                                                    simply, your motherboard is getting full and
                                                    maintaining lists and writing notes to yourself
                                                    is really, really important. This interval is
                                                    when grandparents or great grandparents' wisdom
                                                    prevail. You will begin in this interval to be
                                                    more accepting of the physical changes in your
                                                    body, but it doesn't mean you cannot keep your
                                                    body in top physical shape for this interval.
                                                    Remember to continue to activate self care and
                                                    eat right, and rest, and get plenty of exercise
                                                    to ensure that you remain energized and
                                                    optimized.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div id="review_life_cycle" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="review_life_cycle_text" class="p-2"
                                                 style="height: auto;background-color:#eaf3ff !important;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color:#1C365E !important; text-align: justify; font-size:1rem;background-color:#eaf3ff !important;"><span
                                                        style="color: #1b3a62;font-size:1rem;font-weight:bold;">Cycle of Life - Life Review (75-83):</span><br><br>
                                                    Your current interval begins at age 75 and ends
                                                    at 84. It is in this interval where a “Natural
                                                    Life Review” takes place. It's a time when
                                                    forgiving and forgetting transpires naturally.
                                                    And with this occurrence the onset of surrender
                                                    follows. The beautiful thing about being human
                                                    is that each phase of life holds a place for
                                                    understanding. In this interval, along with
                                                    forgiveness comes a greater acceptance to
                                                    physical changes and consciously choosing how
                                                    you want to engage in life and with others.
                                                    Being more in the moment is a wonderful
                                                    byproduct of this interval.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div id="intro_to_trait" class="col-12 mt-3" style="display: none;">
                                            <div id="intro_to_trait_text" class="card p-2"
                                                 style="height: auto;text-align: justify;background-color:#eaf3ff !important">
                                                <h4 class="" style="color: #1b3a62">{{$trait_intro['public_name']}}</h4>
                                                {!!$trait_intro['description']!!}
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($allStyles as $index => $style)
                                        <div class="row d-flex">
                                            <div id="style_{{$index}}" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="style_{{$index}}_text" class="card p-2"
                                                     style="height: auto; text-align:justify; background-color:#eaf3ff;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style="color: white; text-align: justify; font-size:1rem">
                                                       <span
                                                           style="color: #1b3a62;font-size:1rem;font-weight:bold;">{{$style['public_name'] }} : </span>
                                                        {{-- {{$style['description']}} --}}
                                                        {!!$style['description']!!}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row d-flex">
                                        <div id="your_motivation" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="your_motivation_text" class="card p-2"
                                                 style="height: auto;text-align: justify;background-color:#eaf3ff;">
                                                <h4 class=""
                                                    style="color: #1b3a62">{{$motivation_intro['public_name']}}</h4>

                                                {!!$motivation_intro['description']!!}
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($topTwoFeatures as $index => $feature)
                                        <div class="row d-flex">
                                            <div id="feature_{{$index}}" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="feature_{{$index}}_text" class="card p-2"
                                                     style="height: auto;text-align:justify;background-color:#eaf3ff;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style=" text-align: justify; font-size:1rem;">
                                                                    <span
                                                                        style="color: #1b3a62;font-size:1rem;font-weight:bold;">{{$feature['public_name'] }} : </span>
                                                        {{-- {{$feature['description']}} --}}
                                                        {!!$feature['description']!!}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row d-flex">
                                        <div id="your_boundary" class="col-12 mt-3" style="display: none;">
                                            <div id="your_boundary_text" class="card p-2"
                                                 style="height: auto;text-align: justify;background-color:#eaf3ff;">

                                                <h4 class=""
                                                    style="color: #1b3a62">{{$intro_boundaries['public_name']}}</h4>
                                                {!! $intro_boundaries['description']!!}
                                            </div>
                                        </div>
                                    </div>
                                    @if($boundary)
                                        <div class="row d-flex">
                                            <div id="boundary_dynamic_div" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="boundary_dynamic_div_text" class="card p-2"
                                                     style="height: auto;text-align:justify;background-color:#eaf3ff;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style="color: rgb(160, 174, 192);text-align: justify">
                                                        <span
                                                            style="color: #1b3a62;font-size:1rem;font-weight:bold;">{{$boundary['public_name'] . ' Alchemy' }}  : </span>{!!$boundary['description']!!}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row d-flex">
                                        <div id="your_communication" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="your_communication_text" class="card p-2"
                                                 style="height: auto;text-align: justify;background-color:#eaf3ff;">
                                                <h4 class=""
                                                    style="color: #1b3a62">{{$intro_communication['public_name']}}</h4>
                                                {!!$intro_communication['description']!!}
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($topCommunication as $index => $communication)
                                        <div class="row d-flex">
                                            <div id="communication_{{$index}}" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="communication_{{$index}}_text" class="card p-2"
                                                     style="height: auto;text-align:justify;background-color:#eaf3ff;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style=" text-align: justify; font-size:1rem">
                                                        <span style="color: #1b3a62;font-size:1rem;font-weight:bold;">{{$communication['public_name'] }} : </span>

                                                        {!!$communication['description']!!}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row d-flex">
                                        <div id="energy_pool" class="col-12 mt-3" style="display: none;">
                                            <div id="energy_pool_text" class="card p-2"
                                                 style="height: auto;text-align: justify;background-color:#eaf3ff;">
                                                <h4 class=""
                                                    style="color: #1b3a62">{{$intro_energypool['public_name']}}</h4>
                                                {!!$intro_energypool['description']!!}
                                            </div>
                                        </div>
                                    </div>
                                    @if($energyPool)
                                        <div class="row d-flex">
                                            <div id="energy_pool_dynamic_dev" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="energy_pool_dynamic_dev_text" class="card p-2"
                                                     style="height: auto;text-align:justify;background-color:#eaf3ff;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style="text-align: justify; font-size:1rem"><span
                                                            style="color: #1b3a62;font-size:1rem;font-weight:bold;">{{$energyPool['public_name']}}:</span>

                                                        {!!$energyPool['description']!!}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($perception_life)
                                        <div class="row d-flex">
                                            <div id="your_perception" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="your_perception_text" class="card p-2"
                                                     style="height: auto;text-align: justify;background-color:#eaf3ff;">
                                                    <h4 class=""
                                                        style="color: #1b3a62">{{$perception_life['public_name']}}</h4>
                                                    {!!$perception_life['description']!!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($perception)
                                        <div class="row d-flex">
                                            <div id="perception_dynamic_dev" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="perception_dynamic_dev_text" class="card p-2"
                                                     style="height: auto;text-align:justify;background-color:#eaf3ff;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style=" text-align: justify; font-size:1rem"><span
                                                            style="color: #1b3a62;font-size:1rem;font-weight:bold;">
                                                            {{($perception['code_number'] == 40 ? "Negatively Charged" :
                                                                          ($perception['code_number'] == 41 ? "Neutrally Charged" :
                                                                          ($perception['code_number'] == 42 ? "Positively Charged" : '')))
                                                                        }} [{{ $perception['pv'] ?? '' }}]
                                                        </span>
                                                        {{-- {{$perception['description']}} --}}
                                                        {!!$perception['description']!!}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <ul style="justify-content: space-evenly; background-color: transparent; padding-top: 20px;"
                                        class="nav nav-pills">
                                        @if(App\Helpers\Helpers::getWebUser()->is_admin != 2 && App\Helpers\Helpers::getWebUser()->is_admin != '2')
                                            <li><a href="{{route('admin_user_grid',['id' => $id])}}" target="_blank"
                                                   class="flex-sm-fill text-lg-center nav-link  text-bold">Grid</a>
                                            </li>
                                        @endif
                                        <li class=""><a href="#summaryReport" style=""
                                                        class="flex-sm-fill  text-lg-center nav-link  text-bold {{request()->has('video_url') ? '' : "active"}}"
                                                        data-toggle="tab">Summary Report</a>
                                        </li>
                                        <li><a href="#coreStats"
                                               class="flex-sm-fill text-lg-center nav-link text-bold {{request()->has('video_url') ? 'active' : ""}}"
                                               data-toggle="tab">Full Results</a>
                                        </li>
                                        <li><a href="#dayPlan" class="flex-sm-fill text-lg-center nav-link text-bold"
                                               data-toggle="tab">90 Days Optimization Plan</a>
                                        </li>
                                    </ul>
                                    <div class="container tab-content clearfix">
                                        <div class="tab-pane {{request()->has('video_url') ? '' : "active"}}"
                                             id="summaryReport">
                                            <div class="slider-padding p-3 mt-5 ">
                                                <div class="custom-color">
                                                    {!!$summary_static['description']!!}

                                                </div>
                                                @if($assessment)
                                                    <a href="{{url('admin/download-user-report/'. $assessment->id)}}"
                                                       target="_blank"
                                                       class=" btn updateBtn btn-sm float-start text-white mt-4 mb-0"
                                                       style="background-color: #1b3a62">Download Summary Report</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tab-pane {{request()->has('video_url') ? 'active' : ""}}"
                                             id="coreStats">
                                            <div class="slider-padding p-3 mt-5">
                                                <div class="text-center">
                                                    <h4 class="instruction_text">
                                                        <strong>Click on the Labels to see your Results!</strong>
                                                    </h4>
                                                    <h4 class="instruction_text">
                                                        <strong>CLICK THE LABEL AGAIN TO TOGGLE THE TRANSCRIPT</strong>
                                                    </h4>
                                                </div>
                                                <h4 class="primaryColor">Main Results Introduction:</h4>
                                                <div class="mt-4 custom-color">

                                                    <div id="description-container">
                                                        <div id="description-preview">
                                                            <p>
                                                                {!! Str::words($main_result['description'], 200) !!}
                                                            </p>

                                                        </div>

                                                        <div id="description-full" style="display: none !important;">
                                                            <p>
                                                                {!! $main_result['description'] !!}
                                                            </p>
                                                        </div>

                                                        <p id="toggle-button" style="cursor: pointer;color:#1b3a62"
                                                           onclick="toggleDescription()">read More...</p>
                                                    </div>

                                                </div>

                                                <div class="row d-flex mt-5">
                                                    <div class="col-lg-5 col-sm-12 col-md-6">
                                                        <div id="main_result_intro_heading" class="card"
                                                             style="height: auto; border: 2px solid #1b3a62 !important;">
                                                            <div class="card-body p-3">
                                                                <h5 onclick="showFeatureVideo('{{asset('assets/video/HumanOp ULT Results Intro - Lisa Nelson.mp4')}}', 1, 'main_result_intro')"
                                                                    style="cursor: pointer;color: #1b3a62;"
                                                                    class="fs-10px">
                                                                    Main Results Introduction:
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                              
                                                </div>
                                                <div class="row d-flex mt-5">
                                                    <div class="col-lg-4 col-sm-12 col-md-6">
                                                        <div id="intro_to_cycle_to_life_heading" class="card"
                                                             style="height: auto; border: 2px solid #1b3a62 !important;">
                                                            <div class="card-body p-3">
                                                                <h5 onclick="showFeatureVideo('{{asset('assets/video/Intro to The Cycle of Life.mp4')}}', 1, 'intro_to_cycle_to_life')"
                                                                    style="cursor: pointer;color: #1b3a62;"
                                                                    class="fs-10px">
                                                                    Cycle of Life Introduction:
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(!empty($age))
                                                    @if (7 <= $age && $age <= 11)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="motivation_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 "
                                                                         style="text-align: center">
                                                                        <h5 onclick="showFeatureVideo('{{ asset('assets/video/Cycle of Life - Motivation 16-20.mp4') }}', 1, 'motivation_life_cycle')"
                                                                            style="cursor: pointer"
                                                                            class="fs-10px">
                                                                            Cycle of Life - Connecting & Communicating
                                                                            (7-11)
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row d-flex">
                                                            <div id="motivation_life_cycle" class="col-12 mt-3"
                                                                 style="display: none;">
                                                                <div id="motivation_life_cycle_text" class="card p-2"
                                                                     style="height: auto;background-color:#eaf3ff !important;">
                                                                    <p class="text-sm mt-3 fs-12px"
                                                                       style="color:#1C365E !important;">
                                                                        Your current interval of life between 16 and 20
                                                                        is the time of life when you're essentially test
                                                                        driving the many different motivating forces
                                                                        (also known as drivers) that every human being
                                                                        has access to. These drivers are like passengers
                                                                        in your “vehicle of self.” Those “voices in your
                                                                        head” that align you with certain things, direct
                                                                        you towards certain activities, nudge you to do
                                                                        certain things a certain way, and reveal what
                                                                        your natural talents are. And because this
                                                                        interval is all about test driving these
                                                                        drivers, the many different drivers can have a
                                                                        tendency to compete for the driver's seat during
                                                                        this time of your life, and because of this,
                                                                        these “voices in your head” can often be a bit
                                                                        louder. This can make one feel less focused or
                                                                        even overwhelmed at times. Paying attention to
                                                                        feeding or fueling, your two most prominent
                                                                        drivers is the key to reducing any extra volume
                                                                        you may be experiencing in your head and
                                                                        ensuring greater clarity and energy as you focus
                                                                        on what needs to be fed and honored to reach
                                                                        your goals. You will learn the details around
                                                                        your two most prominent drivers in a following
                                                                        video and applying the understanding you gain
                                                                        about your two drivers can set you on a healthy
                                                                        path of performance and allow you to rise to
                                                                        your full potential naturally.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(12 <= $age && $age <= 15)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="motivation_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 "
                                                                         style="text-align: center">
                                                                        <h5 onclick="showFeatureVideo('{{ asset('assets/video/Cycle of Life - Motivation 16-20.mp4') }}', 1, 'motivation_life_cycle')"
                                                                            style="cursor: pointer"
                                                                            class="fs-10px">
                                                                            Cycle of Life - Alchemical Revelation
                                                                            (12-15)
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row d-flex">
                                                            <div id="motivation_life_cycle" class="col-12 mt-3"
                                                                 style="display: none;">
                                                                <div id="motivation_life_cycle_text" class="card p-2"
                                                                     style="height: auto;background-color:#eaf3ff !important;">
                                                                    <p class="text-sm mt-3 fs-12px"
                                                                       style="color:#1C365E !important;">
                                                                        Your current interval of life between 16 and 20
                                                                        is the time of life when you're essentially test
                                                                        driving the many different motivating forces
                                                                        (also known as drivers) that every human being
                                                                        has access to. These drivers are like passengers
                                                                        in your “vehicle of self.” Those “voices in your
                                                                        head” that align you with certain things, direct
                                                                        you towards certain activities, nudge you to do
                                                                        certain things a certain way, and reveal what
                                                                        your natural talents are. And because this
                                                                        interval is all about test driving these
                                                                        drivers, the many different drivers can have a
                                                                        tendency to compete for the driver's seat during
                                                                        this time of your life, and because of this,
                                                                        these “voices in your head” can often be a bit
                                                                        louder. This can make one feel less focused or
                                                                        even overwhelmed at times. Paying attention to
                                                                        feeding or fueling, your two most prominent
                                                                        drivers is the key to reducing any extra volume
                                                                        you may be experiencing in your head and
                                                                        ensuring greater clarity and energy as you focus
                                                                        on what needs to be fed and honored to reach
                                                                        your goals. You will learn the details around
                                                                        your two most prominent drivers in a following
                                                                        video and applying the understanding you gain
                                                                        about your two drivers can set you on a healthy
                                                                        path of performance and allow you to rise to
                                                                        your full potential naturally.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(16 <= $age && $age <= 20)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="motivation_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 "
                                                                         style="text-align: center">
                                                                        <h5 onclick="showFeatureVideo('{{ asset('assets/video/Cycle of Life - Motivation 16-20.mp4') }}', 1, 'motivation_life_cycle')"
                                                                            style="cursor: pointer"
                                                                            class="fs-10px">
                                                                            Cycle of Life - Motivation (16-20)
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row d-flex">
                                                            <div id="motivation_life_cycle" class="col-12 mt-3"
                                                                 style="display: none;">
                                                                <div id="motivation_life_cycle_text" class="card p-2"
                                                                     style="height: auto;background-color:#eaf3ff !important;">
                                                                    <p class="text-sm mt-3 fs-12px"
                                                                       style="color:#1C365E !important;">
                                                                        Your current interval of life between 16 and 20
                                                                        is the time of life when you're essentially test
                                                                        driving the many different motivating forces
                                                                        (also known as drivers) that every human being
                                                                        has access to. These drivers are like passengers
                                                                        in your “vehicle of self.” Those “voices in your
                                                                        head” that align you with certain things, direct
                                                                        you towards certain activities, nudge you to do
                                                                        certain things a certain way, and reveal what
                                                                        your natural talents are. And because this
                                                                        interval is all about test driving these
                                                                        drivers, the many different drivers can have a
                                                                        tendency to compete for the driver's seat during
                                                                        this time of your life, and because of this,
                                                                        these “voices in your head” can often be a bit
                                                                        louder. This can make one feel less focused or
                                                                        even overwhelmed at times. Paying attention to
                                                                        feeding or fueling, your two most prominent
                                                                        drivers is the key to reducing any extra volume
                                                                        you may be experiencing in your head and
                                                                        ensuring greater clarity and energy as you focus
                                                                        on what needs to be fed and honored to reach
                                                                        your goals. You will learn the details around
                                                                        your two most prominent drivers in a following
                                                                        video and applying the understanding you gain
                                                                        about your two drivers can set you on a healthy
                                                                        path of performance and allow you to rise to
                                                                        your full potential naturally.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(21 <= $age && $age <= 29)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="roadworthy_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 "
                                                                         style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/Cycle of Life - Roadworthy 21-29.mp4')}}', 1, 'roadworthy_life_cycle')"
                                                                            style="cursor: pointer"
                                                                            class="fs-10px">
                                                                            Cycle of Life - Roadworthy (21-29):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(30 <= $age && $age <= 33)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="power_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 "
                                                                         style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/The Cycle of Life - Power Interval 30-33.mp4')}}', 1, 'power_life_cycle')"
                                                                            style="cursor: pointer"
                                                                            class="fs-10px">
                                                                            Cycle of Life - The Power Interval (30-33):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(34 <= $age && $age <= 42)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="mid_life_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 "
                                                                         style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/The Cycle of Life - Mid-Life Transformation 34-43.mp4')}}', 1, 'mid_life_life_cycle')"
                                                                            style="cursor: pointer"
                                                                            class="fs-10px">
                                                                            Cycle of Life - Mid-Life Transformation
                                                                            (34-42):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(43 <= $age && $age <= 51)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="awareness_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 "
                                                                         style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/Cycle of Life - Awareness Interval 43-52.mp4')}}', 1, 'awareness_life_cycle')"
                                                                            style="cursor: pointer"
                                                                            class="fs-10px">
                                                                            Cycle of Life - Awareness (43-51):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(52 <= $age && $age <= 65)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="forward_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 "
                                                                         style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/Cycle of Life - Pay It Forward 52-66.mp4')}}', 1, 'forward_life_cycle')"
                                                                            style="cursor: pointer"
                                                                            class="fs-10px">
                                                                            Cycle of Life - Pay It Forward (52-65):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(66 <= $age && $age <= 69)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="liberated_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 "
                                                                         style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/Cycle of Life - Liberated 66-70.mp4')}}', 1, 'liberated_life_cycle')"
                                                                            style="cursor: pointer"
                                                                            class="fs-10px">
                                                                            Cycle of Life - Liberated (66-69):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(70 <= $age && $age <= 74)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="being_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 "
                                                                         style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/The Cycle of Life - Being 70-75.mp4')}}', 1, 'being_life_cycle')"
                                                                            style="cursor: pointer"
                                                                            class="fs-10px">
                                                                            Cycle of Life - Being (70-74):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(75 <= $age && $age <= 83)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="review_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 "
                                                                         style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/The Cycle of Life - Life Review Interval Ages 75-84.mp4')}}', 1, 'review_life_cycle')"
                                                                            style="cursor: pointer"
                                                                            class="fs-10px">
                                                                            Cycle of Life - Life Review (75-83):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="review_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 "
                                                                         style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/The Cycle of Life - Life Review Interval Ages 75-84.mp4')}}', 1, 'review_life_cycle')"
                                                                            style="cursor: pointer"
                                                                            class="fs-10px">
                                                                            Cycle of Life - Surrender (84+):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                                <div class="row d-flex mt-5">
                                                    <div class="col-lg-4 col-sm-12 col-md-6">
                                                        <div id="intro_to_trait_heading" class="card"
                                                             style="height: auto;">
                                                            <div class="card-body p-3"
                                                                 style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                <h5 onclick="showFeatureVideo('{{asset('assets/video/Intro to Traits.mp4')}}', 1, 'intro_to_trait')"
                                                                    style="cursor: pointer"
                                                                    class="fs-10px">
                                                                    Traits Introduction:
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row d-flex mt-5">

                                                    @foreach($allStyles as $index => $style)
                                                        <div class="col-lg-4 col-sm-12 col-md-6 mb-3">
                                                            <div id="style_{{$index.'_heading'}}" class="card"
                                                                 style="height: auto">
                                                                <div class="card-body p-3 "
                                                                     style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                    <h5 onclick="showFeatureVideo('{{$style['video_url']}}', 1, '{{'style_'.$index}}')"
                                                                        style="cursor: pointer"
                                                                        class="fs-10px">

                                                                        {{-- {{$style[1] . ' [' . "$style[0]" . ']'}} --}}
                                                                        {{ $style['public_name'] . ' [' . $style['code_number'] . ']' }}
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="row d-flex mt-5">
                                                    <div class="col-lg-4 col-sm-12 col-md-6">
                                                        <div id="your_motivation_heading" class="card"
                                                             style="height: auto;">
                                                            <div class="card-body p-3 "
                                                                 style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                <h5 onclick="showFeatureVideo('{{asset('assets/video/Intro to Motivation (Drivers).mp4')}}', 1, 'your_motivation')"
                                                                    style="cursor: pointer"
                                                                    class="fs-10px">
                                                                    Motivation Introduction:
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row d-flex mt-5">
                                                    @foreach($topTwoFeatures as $index => $feature)
                                                        <div class="col-lg-6 col-sm-12 col-md-6">
                                                            <div id="feature_{{$index}}_heading" class="card"
                                                                 style="height: auto">
                                                                <div class="card-body p-3 "
                                                                     style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                    <h5 onclick="showFeatureVideo('{{$feature['video_url']}}', 1, 'feature_{{$index}}')"
                                                                        style="cursor: pointer"
                                                                        class="fs-10px">
                                                                        {{-- {{$index + 1}} --}}
                                                                        {{-- {{$feature[1] . ' [' . "$feature[0]" . ']'}} --}}

                                                                        {{$feature['public_name'] . ' [' . $feature['code_number'] . ']'}}
                                                                    </h5>
                                                                    <div id="{{$feature['public_name']}}"
                                                                         class="collapse description-container"
                                                                         aria-labelledby="headingOne"
                                                                         data-parent="#accordion">
                                                                        <p class="text-sm mt-3 fs-12px"
                                                                           style="color:#1C365E !important;">{{$feature['description']}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="row d-flex mt-5">
                                                    <div class="col-lg-4 col-sm-12 col-md-6">
                                                        <div id="your_boundary_heading" class="card"
                                                             style="height: auto">
                                                            <div class="card-body p-3 "
                                                                 style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                <h5 data-toggle="collapse"
                                                                    data-target="#your_boundaries" aria-expanded="true"
                                                                    aria-controls="your_boundaries"
                                                                    onclick="showFeatureVideo('{{asset('assets/video/Intro to Alchemy.mp4')}}', 1, 'your_boundary')"
                                                                    style="cursor: pointer"
                                                                    class="fs-10px">
                                                                    Intro To Boundaries:
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($boundary)

                                                    <div class="row d-flex mt-5">
                                                        <div class="col-lg-4 col-sm-12 col-md-6">
                                                            <div id="boundary_dynamic_div_heading" class="card"
                                                                 style="height: auto">
                                                                <div class="card-body p-3 "
                                                                     style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                    <h5 onclick="showFeatureVideo('{{$boundary['video_url']}}', 1, 'boundary_dynamic_div')"
                                                                        style="cursor: pointer"
                                                                        class="fs-10px">
                                                                        {{$boundary['public_name'] . ' Alchemy'. " [" . $boundary['code_number'] . "]"}}
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endif
                                                <div class="row d-flex mt-5">
                                                    <div class="col-lg-4 col-sm-12 col-md-6">
                                                        <div id="your_communication_heading" class="card"
                                                             style="height: auto">
                                                            <div class="card-body p-3 "
                                                                 style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                <h5 onclick="showFeatureVideo('{{asset('assets/video/Intro to Communication Style.mp4')}}', 1, 'your_communication')"
                                                                    style="cursor: pointer"
                                                                    class="fs-10px">
                                                                    Intro To Communication Style:
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row d-flex mt-5">

                                                    @foreach($topCommunication as $index => $communication)

                                                        <div class="col-lg-4 col-sm-12 col-md-6 mb-3">
                                                            <div id="communication_{{$index}}_heading" class="card"
                                                                 style="height: auto">
                                                                <div class="card-body p-3 "
                                                                     style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                    <h5 onclick="showFeatureVideo('{{$communication['video_url']}}', 1, 'communication_{{$index}}')"
                                                                        style="cursor: pointer;"
                                                                        class="fs-10px">

                                                                        {{  ' The ' . $communication['public_name'] . ' Energy Center' . ' [' . ($communication['code_number'] ?? null) . ']' }}

                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    @endforeach

                                                </div>
                                                <div class="row d-flex mt-5">
                                                    <div class="col-lg-4 col-sm-12 col-md-6">
                                                        <div id="energy_pool_heading" class="card" style="height: auto">
                                                            <div class="card-body p-3 "
                                                                 style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                <h5 onclick="showFeatureVideo('{{asset('assets/video/Intro to Energy Pool.mp4')}}', 1, 'energy_pool')"
                                                                    style="cursor: pointer;"
                                                                    class="fs-10px">
                                                                    Intro To Energy Pool:
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($energyPool)
                                                    <div class="row d-flex mt-5">
                                                        <div class="col-lg-4 col-sm-12 col-md-6">
                                                            <div id="energy_pool_dynamic_dev_heading" class="card"
                                                                 style="height: auto">
                                                                <div class="card-body p-3 "
                                                                     style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                    <h5 onclick="showFeatureVideo('{{$energyPool['video_url']}}', 1, 'energy_pool_dynamic_dev')"
                                                                        style="cursor: pointer"
                                                                        class="fs-10px">

                                                                        {{$energyPool['public_name']}}

                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($perception_life)
                                                    <div class="row d-flex mt-5">
                                                        <div class="col-lg-4 col-sm-12 col-md-6">
                                                            <div id="your_perception_heading" class="card"
                                                                 style="height: auto">
                                                                <div class="card-body p-3 "
                                                                     style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                    <h5 onclick="showFeatureVideo('{{$perception_life['video_url']}}', 1, 'your_perception')"
                                                                        style="cursor: pointer"
                                                                        class="fs-10px">
                                                                        Intro To Perception of Life:
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endif
                                                @if($perception)
                                                    <div class="row d-flex mt-5">
                                                        <div class="col-lg-4 col-sm-12 col-md-6">
                                                            <div id="perception_dynamic_dev_heading" class="card"
                                                                 style="height: auto">
                                                                <div class="card-body p-3 "
                                                                     style="color: #1b3a62; border: 2px solid #1b3a62 !important; border-radius: 15px;">
                                                                    <h5 onclick="showFeatureVideo('{{$perception['video_url']}}', 1, 'perception_dynamic_dev')"
                                                                        style="cursor: pointer"
                                                                        class="fs-10px">

                                                                        {{--                                                                        {{$perception['public_name'] . (isset($perception->pv) ? ' [' . $perception['pv'] . ']' : "")}}--}}

                                                                        {{($perception['code_number'] == 40 ? "Negatively Charged" :
                                                                          ($perception['code_number'] == 41 ? "Neutrally Charged" :
                                                                          ($perception['code_number'] == 42 ? "Positively Charged" : '')))
                                                                        }} [{{ $perception['pv'] ?? '' }}]

                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="tab-pane" id="dayPlan">
                                            <div class="slider-padding p-3 mt-5">
                                                <div class="w-100">
                                                    <div class="">

                                                        <h2 class="text-white">
                                                            {!! $actionPlan['plan_text'] ?? null !!}
                                                        </h2>

                                                    </div>

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

        function toggleReadmore(button) {
            const content = document.querySelector('.read-more-content');
            content.classList.toggle('hidden');
            const ele = document.getElementById('coreStats');
            if (content.classList.contains('hidden')) {
                button.textContent = 'Read more...';
                ele.scrollIntoView({behavior: 'smooth', block: 'start'})
            } else {
                button.textContent = 'Less more...';
                ele.scrollIntoView({behavior: 'smooth', block: 'start'})
            }


        }

        var video_url = "{{request()->input('video_url', asset('assets/video/HumanOp ULT Results Intro - Lisa Nelson.mp4')) }}";

        var content_id = "{{request()->input('contentName', null) }}"

        showFeatureVideo(video_url, 0, content_id);

        function showFeatureVideo(src, is_core_stats = 0, div_id = null) {

            var video = document.getElementById('myVideo100');
            var videoContainer = document.getElementById('container_video');
            var video_source = document.getElementById('video-source')
            var source = document.createElement('source');

            if (video_source !== null) {

                video_source.remove();
            }

            video.pause();

            source.setAttribute('src', src);
            source.setAttribute('type', 'video/mp4');
            source.setAttribute('id', 'video-source');

            video.appendChild(source);
            video.load();

            if (div_id) {


                $('#' + div_id).toggle();

                if ($('#' + div_id + '_heading').hasClass('orange-border')) {

                    $('#' + div_id + '_heading').removeClass('orange-border');

                    $('#' + div_id + '_text').removeClass('orange-border');
                } else {

                    $('#' + div_id + '_heading').addClass('orange-border');

                    $('#' + div_id + '_text').addClass('orange-border');

                    videoContainer.scrollIntoView();
                }

            }

            // if (is_core_stats){
            //     video.play();
            //
            // }

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


        function toggleDescription() {

            const preview = document.getElementById('description-preview');
            const full = document.getElementById('description-full');
            const button = document.getElementById('toggle-button');

            if (full.style.display === 'none') {

                preview.style.display = 'none';
                full.style.display = 'block';
                button.textContent = 'less More...';
                full.scrollIntoView({behavior: 'smooth', block: 'start'});
            } else {

                preview.style.display = 'block';
                full.style.display = 'none';
                button.textContent = 'read More...';
                preview.scrollIntoView({behavior: 'smooth', block: 'start'});

            }
        }
    </script>

@endpush

