@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])
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

        .orange-border {
            border: 1px solid #f2661c;
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
                                    <div class="video-container show-controls" id="container_video">
                                        <div
                                            class="center-play-pause mx-auto d-flex justify-content-center" style="width: 10%; position: absolute; top:40%; left:45%;">
                                            <button class="btn play-pause-center fs-1"
                                                    style="color: rgb(210, 102, 34);"><i class="fas fa-play"></i>
                                            </button>
                                        </div>
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
                                                                              style="color: rgb(242, 102, 28)"></i>
                                                    </button>
                                                    <input type="range" min="0" max="1" step="any">
                                                    <div class="video-timer">
                                                        <span class="current-time" style="color: #f2661c;">00:00</span>
                                                        <span class="separator" style="color: #f2661c;"> / </span>
                                                        <span class="video-duration"
                                                              style="color: #f2661c;">00:00</span>
                                                    </div>
                                                </li>
                                                <li class="options center">
                                                    <button class="skip-backward"><i class="fas fa-backward"
                                                                                     style="color: #f2661c;"></i>
                                                    </button>
                                                    <button class="play-pause"><i class="fas fa-play"
                                                                                  style="color: #f2661c;"></i>
                                                    </button>
                                                    <button class="skip-forward"><i class="fas fa-forward"
                                                                                    style="color: #f2661c;"></i>
                                                    </button>
                                                </li>
                                                <li class="options right">
                                                    <button class="fullscreen"><i class="fa-solid fa-expand"
                                                                                  style="color: #f2661c;"></i></button>
                                                </li>
                                            </ul>
                                        </div>
                                        <video id="myVideo100" class="w-100 h-100 " style="max-height: 500px;"></video>
                                    </div>

                                    <div class="row d-flex">
                                        <div id="intro_to_cycle_to_life" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="intro_to_cycle_to_life_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192); text-align: justify"><span
                                                        style="color: #f2661c;">Cycle of Life Introduction: </span>
                                                    This video is an introduction to the Cycle of Life. The
                                                    Cycle of Life simply explains our relationship with time
                                                    as it's consistently unfolding for us. Now even though
                                                    each one of us is as unique as our thumb print, and we
                                                    each operate differently on the road of life, the
                                                    element of time remains the same for all of us. The fact
                                                    is time came before us, and it will continue after we
                                                    leave. It's not dependent on us, but our self mastery as
                                                    it relates to understanding the nature of time, will
                                                    help us to make conscious choices that maximize our
                                                    potential as we travel through the different intervals
                                                    of life. So let's take a look at this image of the Cycle
                                                    of life. It somewhat resembles a clock and just like a
                                                    clock, the hands of time are continuously ticking away.
                                                    The clock in this case is a mirror example, essentially,
                                                    representing our shelf life here in this physical world,
                                                    and the 12 unique phases that require special attention.
                                                    Now each number that you see here represents an age, and
                                                    between each age is an interval…and each age interval
                                                    has a certain nature to it. The following video will
                                                    describe the nature of your current interval of life and
                                                    it will also provide you with some strategies that you
                                                    can apply to support yourself as you travel through this
                                                    current interval you're experiencing and help prepare
                                                    you for the next interval of life that you'll eventually
                                                    be entering into.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div id="roadworthy_life_cycle" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="roadworthy_life_cycle_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify"><span
                                                        style="color: #f2661c;">Cycle of Life - Roadworthy (21-29): </span>
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
                                                   style="color: rgb(160, 174, 192);">
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
                                                   style="color: rgb(160, 174, 192);">
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
                                            <div id="power_life_cycle_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);"><span
                                                        style="color: #f2661c;text-align: justify">Cycle of Life - The Power Interval (30-33):</span>
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
                                            <div id="mid_life_life_cycle_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify"><span
                                                        style="color: #f2661c;">Cycle of Life - Mid-Life Transformation (34-42):</span>
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
                                                   style="color: rgb(160, 174, 192);text-align: justify">
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
                                            <div id="awareness_life_cycle_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify"><span
                                                        style="color: #f2661c;">Cycle of Life - Awareness (43-52):</span>
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
                                            <div id="forward_life_cycle_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify"><span
                                                        style="color: #f2661c;">Cycle of Life - Pay It Forward (52-66):</span>
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
                                            <div id="liberated_life_cycle_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify"><span
                                                        style="color: #f2661c;">Cycle of Life - Liberated (66-70): </span>
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
                                            <div id="being_life_cycle_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify"><span
                                                        style="color: #f2661c;">Cycle of Life - Being (70-75):</span>
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
                                            <div id="review_life_cycle_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify"><span
                                                        style="color: #f2661c;">Cycle of Life - Life Review (75-84):</span>
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
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify"><span
                                                        style="color: #f2661c;">Traits Introduction:</span>
                                                    This video is an introduction to traits. Traits are the
                                                    natural physical traits that make you unique. The color
                                                    of your hair and eyes, the shape of your face and other
                                                    physical traits all provide objective insight into your
                                                    natural talents, capabilities and potential. Now there
                                                    are seven key traits and each trait possesses unique
                                                    physical attributes, and also displays certain obvious
                                                    natural characteristics. Each trait has a particular
                                                    function…It's either a thinking trait, a seeing trait or
                                                    a doing trait. Most people are a combination of at least
                                                    three prominent traits (sometimes more). The following
                                                    videos will explore your most prominent traits.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($allStyles as $index => $style)
                                        <div class="row d-flex">
                                            <div id="style_{{$index}}" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="style_{{$index}}_text" class="card p-2"
                                                     style="height: auto;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style="color: rgb(160, 174, 192);text-align: justify"><span style="color: #f2661c;">{{$style[1] }} : </span>{{$style[2]}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row d-flex">
                                        <div id="your_motivation" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="your_motivation_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify"><span
                                                        style="color: #f2661c;">Motivation Introduction:</span>
                                                    This video is an
                                                    introduction to Motivation (or what's driving you to do
                                                    what you do). There are 12 motivators or drivers in
                                                    everyone's “vehicle of self”, but only two are
                                                    authentically in the “front seat” navigating us through
                                                    life. Each one of us has a pilot and a co-pilot, and
                                                    identifying what your two drivers are will help you to
                                                    understand why you do things a certain way and what fuel
                                                    you specifically need every day in order to thrive. The
                                                    following videos will explore your two drivers.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($topTwoFeatures as $index => $feature)
                                        <div class="row d-flex">
                                            <div id="feature_{{$index}}" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="feature_{{$index}}_text" class="card p-2"
                                                     style="height: auto;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style="color: rgb(160, 174, 192);text-align: justify">
                                                                    <span
                                                                        style="color: #f2661c;">{{$feature[1] }} : </span>{{$feature[2]}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row d-flex">
                                        <div id="your_boundary" class="col-12 mt-3" style="display: none;">
                                            <div id="your_boundary_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify"><span
                                                        style="color: #f2661c;">Intro To Boundaries:</span>
                                                    This video is an introduction to boundaries. Boundaries
                                                    refer to what your personal preferences reveal about
                                                    your boundaries of tolerance for people, places and
                                                    things. Now to begin, we invite you to answer some
                                                    questions for yourself to start to become more aware of
                                                    your own boundaries of tolerance as it relates to the
                                                    people, places and things in your life. So here's some
                                                    questions to ask yourself: Are you someone who can leave
                                                    dirty dishes in the sink overnight…or perhaps over
                                                    several nights? If you leave your bed on made in the
                                                    morning, will you think about the unmade bed throughout
                                                    the day? What about camping? Can you do it? And if so,
                                                    how do you camp? Do you require a motorhome? Are you
                                                    more comfortable with just a tent and a book of matches?
                                                    How about the people in your life? Do you have less
                                                    tolerance for those who are less tidy? Or perhaps you
                                                    have less tolerance around those in your life who happen
                                                    to be extremely particular about their space and all
                                                    that they do. The answers to these types of questions
                                                    (and more), reveal your boundaries of tolerance. In
                                                    other words, why you either gain energy or lose energy
                                                    around specific dynamics related to the people, places
                                                    and things in your life.
                                                </p>
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);">
                                                    Now our personal preferences that determine our
                                                    boundaries of tolerance are not learned. They are an
                                                    innate part of our molecular makeup. It's one of the
                                                    elements that makes each one of us so unique. Some
                                                    people, by nature, prefer a more disheveled and organic
                                                    manner of living. Some people prefer to live from a
                                                    practical and flexible perspective. Some people
                                                    naturally prefer a meticulous and particular existence.
                                                    Every human being falls somewhere on a continuum from
                                                    extremely tolerant of disarray and en messes, to very
                                                    tolerant, to somewhat tolerant, to having little to no
                                                    tolerance at all around anything less than a pristine
                                                    environment. Now we use the term Alchemy to describe the
                                                    various levels of refinement, and we use the analogy of
                                                    ore. Gold, Silver and Copper. And one's not better than
                                                    the other, it's not the Olympics…we’re not all trying to
                                                    attain a Gold Alchemy. Everyone's Alchemy is perfect for
                                                    them.
                                                </p>
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);">
                                                    The people, places and things in our life range from a
                                                    Gold to a Copper Alchemy, and your unique Alchemy is
                                                    determined by the combined quantity of each ore that you
                                                    possess. The more Gold in you, the more meticulous you
                                                    are. The more Silver in you, the more flexible and
                                                    adaptable you are. The more Copper in you, the more low
                                                    maintenance and organic you are.
                                                </p>
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);">
                                                    So let's take a look at how the Gold Alchemy represents
                                                    itself through some images. Gold aligns with quality.
                                                    Where they live, where and how they travel, and the
                                                    kinds of creature comforts that they select are usually
                                                    of quality or mimic something of high quality quite
                                                    well. Their living and workspaces are more minimalistic.
                                                    So in other words, no extra things laying about.
                                                    Everything has its place. And as it relates to travel,
                                                    individuals with a Gold Alchemy seek quality
                                                    destinations and quality accommodations. Now an
                                                    important thing to remember is Gold can sometimes be
                                                    perceived by others as having obsessive tendencies. But
                                                    the reality is they simply put great attention on those
                                                    details that keep the standards high for themselves and
                                                    in their environment. Overall, the Gold Alchemy is
                                                    highly refined. Gold’s seek the best of the best and
                                                    desire to align with the best of the best. It really is
                                                    the gold standard that sets things apart for them.
                                                </p>
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);">
                                                    The Silver a represents itself as practical. So in
                                                    taking a look at the living and workspaces you see here,
                                                    everything is neat and practical, there's a functional
                                                    feel to the living room, animals can be on the couches;
                                                    organized piles on the desk; the hammock image reflects
                                                    versatility and experiencing nature. Overall, the Silver
                                                    Alchemy is very accepting and tolerant of a wide range
                                                    of people,places and things. The world is essentially at
                                                    their disposal. They can flex whenever they need to.
                                                </p>
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);">
                                                    Now the Copper Alchemy aligns with utility. In other
                                                    words, getting the most use out of everything. Copper’s
                                                    simply have a more relaxed way of living and being. When
                                                    we look at these images of examples of Copper work and
                                                    living spaces, we can see there's a great tolerance for
                                                    messes. Copper’s tend to thrive in more disheveled, very
                                                    lived in, environments. Even when they travel, Copper's
                                                    don't necessarily need a fancy Rv. There's an organic
                                                    quality to all that they do and how they do it. Now,
                                                    Copper’s can get a bad rap at times from others for
                                                    being slobs and careless… when in fact, the intention
                                                    that lies behind their performance is never directed at
                                                    trying to achieve mediocrity or destruction. It's just
                                                    an intentional creation for reasons of personal comfort…
                                                    again, they thrive in a more dish environment. Overall,
                                                    the Copper Alchemy possesses an easy going approach to
                                                    life. They tend not to judge others and they encourage
                                                    others to just relax.
                                                </p>
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);">
                                                    It's really important to recognize that as humans we
                                                    have different boundaries of tolerance. And, the number
                                                    one reason why relationships are challenged is when
                                                    those boundaries are stretched too far between you and
                                                    certain people, places and things in your life. What
                                                    happens is this causes incompatibility in the moment as
                                                    it relates to your relationship with those certain
                                                    people, places and things and the inca generates energy
                                                    drain. The longer you stay in the state of energy drain,
                                                    the greater the potential for challenges to ignite, and
                                                    even escalate… within yourself and in relating to
                                                    others. So understanding the nature of your Alchemy will
                                                    help you to gain clarity around your boundaries of
                                                    tolerance, and help you to make conscious choices every
                                                    day to ensure that you're maintaining alignment with
                                                    your Alchemy.
                                                </p>
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);">
                                                    Now your next step is to watch the following video that
                                                    describes the nature of your unique Alchemy. And as you
                                                    begin to understand your Alchemy, we invite you to
                                                    practice becoming aware when you feel your boundaries of
                                                    tolerance for people, places and things are being
                                                    stretched too far. And you can recognize this when you
                                                    notice your energy beginning to wane or drain. We
                                                    recommend when this happens that you make the choice to
                                                    step away in order to avoid excessive energy drain or
                                                    ignite unnecessary challenges for yourself or with
                                                    others.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($boundary)
                                        <div class="row d-flex">
                                            <div id="boundary_dynamic_div" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="boundary_dynamic_div_text" class="card p-2"
                                                     style="height: auto;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style="color: rgb(160, 174, 192);text-align: justify">
                                                        {{$boundary['text']}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row d-flex">
                                        <div id="your_communication" class="col-12 mt-3"
                                             style="display: none;">
                                            <div id="your_communication_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify"><span
                                                        style="color: #f2661c;">Intro To Communication Style:</span>
                                                    This video is an introduction to Communication Style.
                                                    Energy Centers define your Communication Style. In other
                                                    words, how you are presently wired to engage in life.
                                                    There are four Energy Centers: Emotional, Instinctual,
                                                    Intellectual and Moving. Every person has all four and
                                                    each Center has a volume that is unique to you. Your
                                                    most pronounced Energy Center determines how you connect
                                                    with yourself first, and how you express and respond
                                                    first in processing contact with people, places and
                                                    things. The four energy centers are like the doors to
                                                    your “house of self”. Your most pronounced or what we
                                                    call primary Energy Center, is essentially your “front
                                                    door”. It determines the primary way you connect with
                                                    yourself and your environment, and it determines how you
                                                    relate to others - first. And when you know how to
                                                    unlock or open that front door, you can make a
                                                    successful first connection. But we cannot ignore the
                                                    other three “doors”. Many people don't retain
                                                    information because they're not fully integrating the
                                                    information. When you open your “doors” in the unique
                                                    order in which you are configured, two things happen.
                                                    First, you fully integrate the experience of the moment.
                                                    And second, you ensure that the information from that
                                                    moment is retained and available for future use. So
                                                    essentially you fully downloaded that experience. Now,
                                                    the Energy Center configuration that you see here is an
                                                    example. This person's “front door” is their Emotional
                                                    center. And when they open the door to their Emotional
                                                    Center by being in the company of living things,
                                                    physical touch, expressing what they're feeling, that
                                                    “front door” opens, and an initial connection is made.
                                                    When this person in turn opens up their other three
                                                    doors, in this ordered sequence, a full download will
                                                    result, and optimal communication can occur for them.
                                                </p>
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify">
                                                    Now the four following videos describe the nature of
                                                    each of the four Energy Center. They are presented to
                                                    you in your uniquely ordered sequence (or
                                                    configuration), and we encourage you to pay special
                                                    attention to your first or primary Energy Center, as
                                                    this is how you make your initial connection. Now,
                                                    because Energy Centers play such an important role in
                                                    how we connect, communicate and commit experiences to
                                                    memory, one of the best practices you can do first thing
                                                    every morning, and then throughout your day, whenever
                                                    fatigue of any kind creeps in, is to open up all four
                                                    doors in your uniquely ordered sequence. So when you
                                                    watch each Energy Center video, consider for yourself
                                                    quick strategies you can activate on the fly in the
                                                    moment to “open each door”.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($topCommunication as $index => $communication)
                                        <div class="row d-flex">
                                            <div id="communication_{{$index}}" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="communication_{{$index}}_text" class="card p-2"
                                                     style="height: auto;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style="color: rgb(160, 174, 192);text-align: justify">
                                                        <span style="color: #f2661c;">{{$communication['public_name'] }} : </span>{{$communication['description']}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row d-flex">
                                        <div id="energy_pool" class="col-12 mt-3" style="display: none;">
                                            <div id="energy_pool_text" class="card p-2"
                                                 style="height: auto;">
                                                <p class="text-sm mt-3 fs-12px"
                                                   style="color: rgb(160, 174, 192);text-align: justify"><span
                                                        style="color: #f2661c;">Intro To Energy Pool:</span>
                                                    This video is an introduction to Energy Pool. Your
                                                    Energy Pool represents how much physical energy you have
                                                    to expend on a daily basis. And here are some important
                                                    points to know about your Energy Pool …first, knowing
                                                    that not everyone is working with the same amount of
                                                    energy. Think about it for a minute. Just like the many
                                                    different cars on the road are various sizes and have
                                                    various size fuel tanks, your body is the vehicle
                                                    transporting your thoughts around in this life, and some
                                                    of you have bigger “fuel tanks” or larger “pools of
                                                    energy” than others… just by the nature of your
                                                    physicality. Another point is your Energy Pool can
                                                    fluctuate. When you're extremely fatigued your Energy
                                                    Pool can lower and when you're fully optimized it can
                                                    rise a bit… or even for some of you - a lot. Some
                                                    activities, choices, people, places and things can rob
                                                    you of vital energy. And depending upon the nature of
                                                    those dynamics, you may or may not be able to recoup the
                                                    energy. The most important point is to recognize when
                                                    you focus on making intelligent choices about what you
                                                    uniquely need in order to optimize your current state,
                                                    you become energy efficient…no matter what the size of
                                                    your Energy Pool. The following video describes the
                                                    nature of your Energy Pool.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($energyPool)
                                        <div class="row d-flex">
                                            <div id="energy_pool_dynamic_dev" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="energy_pool_dynamic_dev_text" class="card p-2"
                                                     style="height: auto;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style="color: rgb(160, 174, 192);text-align: justify"><span
                                                            style="color: #f2661c;">{{$energyPool['public_name'] . ' [' . $energyPool['id'] . ']'}}:</span>
                                                        {{$energyPool['text']}}
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
                                                     style="height: auto;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style="color: rgb(160, 174, 192);text-align: justify"><span
                                                            style="color: #f2661c;">Intro To Perception of Life:</span>
                                                        This video is an introduction to Perception. Your
                                                        Perception of Life defines your electromagnetic
                                                        potential. It reveals whether you perceive what is
                                                        working first, what's not, or whether you have the
                                                        ability to perceive both perspectives at the same
                                                        time. It's really the glass half full, half empty
                                                        dynamic. Some people, by nature, are glass half full
                                                        people. Some people, by nature, are glass not full
                                                        enough people, and some people land right in the
                                                        middle, on the surface of that water, perceiving the
                                                        space above and the fullness below simultaneously.
                                                        So let's break it down a little more…just like
                                                        magnets, possess poles allowing for attraction and
                                                        repulsion, people do as well. Some perceive and
                                                        express what's working first, and they are what we
                                                        call positively charged. Some people perceive and
                                                        express what's not working first, and they are what
                                                        we call negatively charged. And some people perceive
                                                        both perspectives simultaneously, and they are
                                                        neutrally charged. All perceptions are good, valued
                                                        and needed. Positively charged people are more
                                                        solution-oriented. Negatively charged people are
                                                        more problem-oriented. And neutrally charged people
                                                        can perceive the whole picture. And because of this,
                                                        there will be a slight delay in expressing their
                                                        perception while they weigh both sides. Positively
                                                        charged people will naturally attract individuals
                                                        who are somewhat more negatively charged…there's an
                                                        actual bonding attraction that occurs - just like
                                                        those magnets. And vice versa, the negatively
                                                        charged people will naturally attract individuals
                                                        who are more (or somewhat more) positively charged.
                                                        Now, when two negatively charged people interact,
                                                        they will actually experience an electromagnetic
                                                        repulsion. And when both are not operating at their
                                                        optimum, two negatively charged people can easily
                                                        make meaning out of what simply began as a natural
                                                        energetic repulsion. And unfortunately, sparks can
                                                        potentially fly. So understanding your Perception of
                                                        Life is an essential ingredient in cultivating
                                                        tolerance and harmony. First and foremost, with
                                                        yourself, and of course, in relating to others. When
                                                        we understand these differences in Perceptions of
                                                        Life, we can begin to honor and see the value that
                                                        exists in the differences between each one of us and
                                                        avoid attempting to change another's way of
                                                        experiencing the moment. The following video
                                                        describes your Perception of Life.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($perception)
                                        <div class="row d-flex">
                                            <div id="perception_dynamic_dev" class="col-12 mt-3"
                                                 style="display: none;">
                                                <div id="perception_dynamic_dev_text" class="card p-2"
                                                     style="height: auto;">
                                                    <p class="text-sm mt-3 fs-12px"
                                                       style="color: rgb(160, 174, 192);text-align: justify"><span
                                                            style="color: #f2661c;">{{$perception['public_name'] . (isset($perception->pv) ? ' [' . $perception['pv'] . ']' : "")}}</span>
                                                        {{$perception['text']}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <ul style="justify-content: space-evenly; background-color: transparent; padding-top: 20px;"
                                        class="nav nav-pills">
                                        <li><a href="#summaryReport"
                                               class="flex-sm-fill text-lg-center nav-link text-white text-bold {{request()->has('video_url') ? '' : "active"}}"
                                               data-toggle="tab">Summary Report</a>
                                        </li>
                                        <li><a href="#coreStats"
                                               class="flex-sm-fill text-lg-center nav-link text-white {{request()->has('video_url') ? 'active' : ""}}"
                                               data-toggle="tab">Full Results</a>
                                        </li>
                                        <li><a href="#dayPlan" class="flex-sm-fill text-lg-center nav-link text-white"
                                               data-toggle="tab">90 Days Optimization Plan</a>
                                        </li>
                                    </ul>
                                    <div class="container tab-content clearfix">
                                        <div class="tab-pane {{request()->has('video_url') ? '' : "active"}}"
                                             id="summaryReport">
                                            <div class="slider-padding p-3 mt-5">
                                                <p>The HumanOp Summary Report serves to identify those aspects about you
                                                    that define and direct your best performance qualities. Since your
                                                    physical being is respectively the assigned vehicle transporting you
                                                    through life, it's often helpful to know what kind of vehicle you
                                                    are. The Greeks have been insisting we "Know Thyself" for centuries.
                                                    This simple request answered can facilitate success in all aspects
                                                    of life, including one's performance in conducting business, and
                                                    creating healthy relationships at work and in life.</p>
                                                <p>The HumanOp Assessment is a patented instrument grounded in physical
                                                    laws and objective scientific understanding. It collects and
                                                    quantifies information in a user-friendly format, providing easily
                                                    comprehensible results.</p>
                                                <p>Your personal HumanOp Summary Report provides you with your own
                                                    operating manual. These operating guidelines support you in making
                                                    conscious choices that keep you energized and optimized. When you
                                                    use your natural talents versus learned talents you gain energy.
                                                    Maximizing your fuel efficiency allows you to access your true self
                                                    and enjoy life in the process.</p>
                                                <h4 class="primaryColor">The HumanOp Summary Report proves valuable in
                                                    various contexts:</h4>
                                                <ul>
                                                    <li>Employer and agency recruitment</li>
                                                    <li>Relationship management</li>
                                                    <li>Psychotherapy</li>
                                                    <li>Career guidance</li>
                                                    <li>Premarital counseling</li>
                                                    <li>Roommate selection</li>
                                                    <li>Self-understanding and interpersonal relationships</li>
                                                </ul>

                                                @if($assessment)
                                                    <a href="{{url('client/download-user-report/'. $assessment->id)}}"
                                                       target="_blank"
                                                       class=" btn updateBtn btn-sm float-start text-white mt-4 mb-0"
                                                       style="background-color: #f2661c">Download Summary Report</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tab-pane {{request()->has('video_url') ? 'active' : ""}}"
                                             id="coreStats">
                                            <div class="slider-padding p-3 mt-5">

                                                <h4 class="primaryColor">Main Results Introduction:</h4>
                                                <p class="mt-4">You're about to experience your Human Op ULT assessment
                                                    results. Most people find this experience to be extremely
                                                    insightful, validating and even empowering to learn about themselves
                                                    from this objective natural perspective. I want to spend just a few
                                                    minutes with you and share a little bit about what makes our
                                                    technology so unique. It's the objectivity of the HumanOp ULT
                                                    assessment that sets us apart from the thousands of other human
                                                    assessments. Now you may have experienced other human assessments
                                                    over the years, and we're certainly not here to dismiss any one of
                                                    them. Our assessment is just different from those others. The others
                                                    fall under the umbrella of psychometric assessments. They're asking
                                                    your opinion about yourself, and, of course, what you believe to be
                                                    true about yourself is important. It certainly matters. And as we
                                                    know, if we solely rely on our subjective opinions about ourselves,
                                                    we can also recognize that sometimes our opinions of ourselves can
                                                    be skewed. Our assessment technology elicits the objectivity that
                                                    exists in human nature because we address the physics that exists in
                                                    all of nature. After all, physical law governs this physical world
                                                    in which we live. It is a constant. It's never changing. It governs
                                                    all living things, plants, trees, animals…and human beings. So those
                                                    questions that we ask you about what you look like, objectively, the
                                                    answers actually mean something in terms of how you naturally
                                                    operate on the road of life and our technology quantifies this
                                                    understanding.
                                                    <br>
                                                    <a href="javascript:void(0)" data-bs-toggle="modal"
                                                       data-bs-target="#resultIntroductionModal"
                                                       style="color: #f2661c;">read
                                                        more...
                                                    </a>
                                                </p>
                                                <div class="row d-flex mt-5">
                                                    <div class="col-lg-4 col-sm-12 col-md-6">
                                                        <div id="intro_to_cycle_to_life_heading" class="card"
                                                             style="height: auto;">
                                                            <div class="card-body p-3">
                                                                <h5 onclick="showFeatureVideo('{{asset('assets/video/Intro to The Cycle of Life.mp4')}}', 1, 'intro_to_cycle_to_life')"
                                                                    style="cursor: pointer;color: #f2661c;"
                                                                    class="fs-10px">
                                                                    Cycle of Life Introduction:
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(!empty($age))
                                                    @if(16 <= $age && $age <= 20)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="motivation_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 ">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/Cycle of Life - Motivation 16-20.mp4')}}', 1, 'motivation_life_cycle')"
                                                                            style="cursor: pointer;"
                                                                            class="text-white fs-10px">
                                                                            Cycle of Life - Motivation (16-20):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row d-flex">
                                                            <div id="motivation_life_cycle" class="col-12 mt-3"
                                                                 style="display: none;">
                                                                <div id="motivation_life_cycle_text" class="card p-2"
                                                                     style="height: auto;">
                                                                    <p class="text-sm mt-3 fs-12px"
                                                                       style="color: rgb(160, 174, 192);">
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
                                                                    <div class="card-body p-3 ">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/Cycle of Life - Roadworthy 21-29.mp4')}}', 1, 'roadworthy_life_cycle')"
                                                                            style="cursor: pointer;"
                                                                            class="text-white fs-10px">
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
                                                                    <div class="card-body p-3 ">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/The Cycle of Life - Power Interval 30-33.mp4')}}', 1, 'power_life_cycle')"
                                                                            style="cursor: pointer;"
                                                                            class="text-white fs-10px">
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
                                                                    <div class="card-body p-3 ">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/The Cycle of Life - Mid-Life Transformation 34-43.mp4')}}', 1, 'mid_life_life_cycle')"
                                                                            style="cursor: pointer;"
                                                                            class="text-white fs-10px">
                                                                            Cycle of Life - Mid-Life Transformation
                                                                            (34-42):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(43 <= $age && $age <= 52)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="awareness_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 ">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/Cycle of Life - Awareness Interval 43-52.mp4')}}', 1, 'awareness_life_cycle')"
                                                                            style="cursor: pointer;"
                                                                            class="text-white fs-10px">
                                                                            Cycle of Life - Awareness (43-52):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(52 <= $age && $age <= 66)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="forward_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 ">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/Cycle of Life - Pay It Forward 52-66.mp4')}}', 1, 'forward_life_cycle')"
                                                                            style="cursor: pointer;"
                                                                            class="text-white fs-10px">
                                                                            Cycle of Life - Pay It Forward (52-66):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(66 <= $age && $age <= 70)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="liberated_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 ">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/Cycle of Life - Liberated 66-70.mp4')}}', 1, 'liberated_life_cycle')"
                                                                            style="cursor: pointer;"
                                                                            class="text-white fs-10px">
                                                                            Cycle of Life - Liberated (66-70):
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif(70 <= $age && $age <= 75)
                                                        <div class="row d-flex mt-5">
                                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                                <div id="being_life_cycle_heading" class="card"
                                                                     style="height: auto">
                                                                    <div class="card-body p-3 ">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/The Cycle of Life - Being 70-75.mp4')}}', 1, 'being_life_cycle')"
                                                                            style="cursor: pointer;"
                                                                            class="text-white fs-10px">
                                                                            Cycle of Life - Being (70-75):
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
                                                                    <div class="card-body p-3 ">
                                                                        <h5 onclick="showFeatureVideo('{{asset('assets/video/The Cycle of Life - Life Review Interval Ages 75-84.mp4')}}', 1, 'review_life_cycle')"
                                                                            style="cursor: pointer;"
                                                                            class="text-white fs-10px">
                                                                            Cycle of Life - Life Review (75-84):
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
                                                            <div class="card-body p-3">
                                                                <h5 onclick="showFeatureVideo('{{asset('assets/video/Intro to Traits.mp4')}}', 1, 'intro_to_trait')"
                                                                    style="cursor: pointer;color: #f2661c;"
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
                                                                <div class="card-body p-3 ">
                                                                    <h5 onclick="showFeatureVideo('{{$style[3]}}', 1, '{{'style_'.$index}}')"
                                                                        style="cursor: pointer;"
                                                                        class="text-white fs-10px">
                                                                        {{$index + 1}}
                                                                        . {{$style[1] . ' [' . "$style[0]" . ']'}}
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
                                                            <div class="card-body p-3 ">
                                                                <h5 onclick="showFeatureVideo('{{asset('assets/video/Intro to Motivation (Drivers).mp4')}}', 1, 'your_motivation')"
                                                                    style="cursor: pointer;color: #f2661c;"
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
                                                                <div class="card-body p-3 ">
                                                                    <h5 onclick="showFeatureVideo('{{$feature[3]}}', 1, 'feature_{{$index}}')"
                                                                        style="cursor: pointer;"
                                                                        class="text-white fs-10px">
                                                                        {{$index + 1}}
                                                                        . {{$feature[1] . ' [' . "$feature[0]" . ']'}}
                                                                    </h5>
                                                                    <div id="{{$feature[1]}}"
                                                                         class="collapse description-container"
                                                                         aria-labelledby="headingOne"
                                                                         data-parent="#accordion">
                                                                        <p class="text-sm mt-3 fs-12px"
                                                                           style="color: rgb(160, 174, 192);">{{$feature[2]}}</p>
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
                                                            <div class="card-body p-3 ">
                                                                <h5 data-toggle="collapse"
                                                                    data-target="#your_boundaries" aria-expanded="true"
                                                                    aria-controls="your_boundaries"
                                                                    onclick="showFeatureVideo('{{asset('assets/video/Intro to Alchemy.mp4')}}', 1, 'your_boundary')"
                                                                    style="cursor: pointer;color: #f2661c;"
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
                                                                <div class="card-body p-3 ">
                                                                    <h5 onclick="showFeatureVideo('{{$boundary['video_url']}}', 1, 'boundary_dynamic_div')"
                                                                        style="cursor: pointer;"
                                                                        class="text-white fs-10px">
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
                                                            <div class="card-body p-3 ">
                                                                <h5 onclick="showFeatureVideo('{{asset('assets/video/Intro to Communication Style.mp4')}}', 1, 'your_communication')"
                                                                    style="cursor: pointer;color: #f2661c;"
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
                                                                <div class="card-body p-3 ">
                                                                    <h5 onclick="showFeatureVideo('{{$communication['video_url']}}', 1, 'communication_{{$index}}')"
                                                                        style="cursor: pointer;"
                                                                        class="text-white fs-10px">

                                                                        {{'The ' . $communication['public_name'] . ' Energy Center'}}

                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    @endforeach

                                                </div>
                                                <div class="row d-flex mt-5">
                                                    <div class="col-lg-4 col-sm-12 col-md-6">
                                                        <div id="energy_pool_heading" class="card" style="height: auto">
                                                            <div class="card-body p-3 ">
                                                                <h5 onclick="showFeatureVideo('{{asset('assets/video/Intro to Energy Pool.mp4')}}', 1, 'energy_pool')"
                                                                    style="cursor: pointer;color: #f2661c;"
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
                                                                <div class="card-body p-3 ">
                                                                    <h5 onclick="showFeatureVideo('{{$energyPool['video_url']}}', 1, 'energy_pool_dynamic_dev')"
                                                                        style="cursor: pointer;"
                                                                        class="text-white fs-10px">

                                                                        {{$energyPool['public_name'] . ' [' . $energyPool['id'] . ']'}}

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
                                                                <div class="card-body p-3 ">
                                                                    <h5 onclick="showFeatureVideo('{{$perception_life['video_url']}}', 1, 'your_perception')"
                                                                        style="cursor: pointer;color: #f2661c;"
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
                                                                <div class="card-body p-3 ">
                                                                    <h5 onclick="showFeatureVideo('{{$perception['video_url']}}', 1, 'perception_dynamic_dev')"
                                                                        style="cursor: pointer;"
                                                                        class="text-white fs-10px">

                                                                        {{$perception['public_name'] . (isset($perception->pv) ? ' [' . $perception['pv'] . ']' : "")}}

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
    {{--Main Result Introduction modal--}}
    <div class="modal fade" id="resultIntroductionModal" tabindex="-1" role="dialog"
         aria-labelledby="resultIntroductionModal"
         aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fs-4" style="color: #f2661c;">Main Results
                                    Introduction:</label>
                                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <p class="text-white mt-4" style="text-align: justify">You're about to experience your
                                    Human Op ULT assessment results. Most people find
                                    this experience to be extremely insightful, validating and even empowering to learn
                                    about themselves from this objective natural perspective. I want to spend just a few
                                    minutes with you and share a little bit about what makes our technology so unique.
                                    It's the objectivity of the HumanOp ULT assessment that sets us apart from the
                                    thousands of other human assessments. Now you may have experienced other human
                                    assessments over the years, and we're certainly not here to dismiss any one of them.
                                    Our assessment is just different from those others. The others fall under the
                                    umbrella of psychometric assessments. They're asking your opinion about yourself,
                                    and, of course, what you believe to be true about yourself is important. It
                                    certainly matters. And as we know, if we solely rely on our subjective opinions
                                    about ourselves, we can also recognize that sometimes our opinions of ourselves can
                                    be skewed. Our assessment technology elicits the objectivity that exists in human
                                    nature because we address the physics that exists in all of nature. After all,
                                    physical law governs this physical world in which we live. It is a constant. It's
                                    never changing. It governs all living things, plants, trees, animals…and human
                                    beings. So those questions that we ask you about what you look like, objectively,
                                    the answers actually mean something in terms of how you naturally operate on the
                                    road of life and our technology quantifies this understanding. </p>
                                <p class="text-white" style="text-align: justify">Let's take a look at a very elementary
                                    illustration of what this looks like in
                                    nature. As humans, we can look at most things in nature, and most of the time we
                                    know the potential of what we're looking at. Just by looking at it. Take these two
                                    dogs, for example. If you knew nothing about these two animals, and there's no
                                    judgment about either dog, you can notice certain things about each one. You can
                                    notice that the one on the left is likely going to take a more direct approach to
                                    life. Now it doesn't mean he's going to be mean, just more direct. You can see this
                                    because you can see his powerhouse build. When you look at the dog on the right, you
                                    notice it's probably going to take a more distracted approach to life. More happy
                                    jolly go lucky. And again, you're not judging it, you're just seeing its potential
                                    just by observing the nature of its physicality. </p>
                                <p class="text-white" style="text-align: justify">Let's take the same concept into
                                    nature a little bit deeper and look at the cactus in
                                    the willow. And, of course, they don't both grow in the same environment. But if
                                    they did and we wanted to picnic, the willow would obviously be the better choice
                                    for our picnic needs. When we look at that cactus, we know certain things about it
                                    just by looking at it. We know that it thrives in a hot dry climate, that it doesn't
                                    need a lot of water, and that we probably don't want to hug it. The willow on the
                                    other hand thrives in a cooler wet climate…needs more water, and I could go on and
                                    on, but you're getting the point here. As humans we can look at plants and trees and
                                    animals, and most of the time we know what we are looking at. In other words, we
                                    know the potential that stands before us just by looking at it. Human nature is no
                                    different. Our physicality reveals our true nature. Some people by nature are more
                                    direct, kind like that pitbull, and it actually shows up in their physical traits.
                                    They do actually have more of a powerhouse build kind of like the pit bull looks.
                                    Some people are more like that willow tree. They provide shade and in the human
                                    equation that shade equates to they're actually more naturally nurturing. And again,
                                    we can see it in their physicality. </p>
                                <p class="text-white" style="text-align: justify">So humans can see natural potential
                                    when they look into nature, but when they look at
                                    themselves, or another person, they often don't know what they are looking at. They
                                    don't see the unique potential of what stands before them. They may know “who”
                                    stands before them, but they don't know the objective nature of the individual. The
                                    “what”, if you will. So unfortunately, they often end up subscribing to ways of
                                    being for themselves that are not natural for them, based on societal recommendation
                                    and cultural norms…upbringing. All of those things are not wrong, but sometimes all
                                    of those factors in our life are just not in alignment with our true nature, and
                                    they add to those opinions that we have about ourselves that aren't quite accurate.
                                    Humans also impose their own expectations on others. Simply because something works
                                    really great for them, and they just assume it will work really great for another.
                                    The reality is, every person is as unique as our thumb print. And as we know, there
                                    are no two thumb prints exactly alike. And our physical bodies are essentially the
                                    vehicles that are transporting us through this life. By nature, we are not designed
                                    to be doing the same things in the same way. Let's take a look at the Hummer and the
                                    Lamborghini, side by side. We know one of them is designed to off-road, and one of
                                    them isn't. It's really a great example of when we operate against our true nature
                                    or the nature of our design. We essentially burn out. You know, it's a really
                                    important reminder, as humans we can learn how to do most anything. But when we
                                    apply things to our life and they're not in alignment with how we naturally show up,
                                    it will take energy from us. Your assessment results will help you understand what
                                    you naturally need on a daily basis in order to reach your highest performance
                                    potential to ensure that you get the most out of life. Now, the methodology behind
                                    our technology is called The Knowledge of Y.O.U. There are six key components. When
                                    you receive your assessment results, we will walk you through each component one by
                                    one. We’ll give you an overview of the component, and then we'll share with you how
                                    you show up through that component. So here are the six components: Traits,
                                    Motivations, Boundaries, Communication style, and then there's a dual component at
                                    the bottom Energy and Perception. </p>
                                <p class="text-white" style="text-align: justify">Now there's one last very important
                                    aspect that you will experience in your results
                                    session. And it relates to time. Everything that we can see with the naked eye in
                                    this physical world points to a clue about human nature. Notice that this graphic,
                                    we call it the Cycle of Life resembles a clock, The clock in this case is a mirror
                                    example representing our shelf life..the time we spend in this lifetime, and the 12
                                    phases that require special attention. Each number that you see here represents an
                                    age, and in between each age is an interval that has a certain nature to it. In your
                                    results review session, we will help you to understand how to best support yourself
                                    as you travel through the current age agent interval that you're experiencing right
                                    now and even help prepare you for the next interval of life that you'll eventually
                                    be entering into. The Cycle of Life is a very, very important reminder that we were
                                    all at the effect of time, and knowing yourself through our technology will help you
                                    to make the most of that time. We very much look forward to sharing your assessment
                                    results with you</p>
                            </div>
                        </div>
                    </div>
                </div>
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

@endpush

