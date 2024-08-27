<!DOCTYPE html>
<html>
<body>
<div class="row">
    <div class="col-lg-12 position-relative z-index-2">
        <div class="container-fluid">
            <section>
                <div class="row mt-lg-4 mt-2">
                    <div class="col-12">
                        <div class="card" style="text-align: center">
                            <div class="card-body p-3 ">
                                <div>
                                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/ultlogo.png'))) }}"
                                         style="background:#351a0d; padding: 0px; max-width: 500px; border-radius: 5px;">
                                </div>

                                <div class="text-white">“Advanced Human Assessment Technology for a Better
                                    Mankind”
                                </div>
                                <h1 class="text-white">ULT Summary Report</h1>
                                <h4 class="text-white">{{$reports['user_name']}}
                                    , {{$reports['user_gender'] == 0 ? 'Male' : 'Female'}}, Interval</h4>
                                <div class="text-white mt-4" style="text-align: justify">
                                    The ULT Performance Report serves to identify those aspects about you that
                                    define and direct your best
                                    performance qualities. Since your physical being is respectively the assigned
                                    vehicle transporting you through
                                    this lifetime, it's often helpful to know what kind of vehicle you are. The
                                    Greeks have been insisting we "Know
                                    Thyself" for centuries. This simple request answered can facilitate success in
                                    all aspects of life including
                                    one's performance in conducting business and creating healthy relationships at
                                    work and in life. The ULT
                                    technology is a patented instrument registered and branded as The Ultimate Life
                                    Tool. The methodology serving as
                                    the foundation for its development is referred to as The Knowledge of Y.O.U.
                                    (your own understanding). This
                                    cumulative insight is older than the language of man and is founded in physical
                                    law and scientific objective
                                    understanding. The ULT assessment tool queries and quantifies information and
                                    identifies results in a manner
                                    that can be easily understood. Your personal ULT Performance Report introduces
                                    you to Y.O.U. and provides you
                                    with your own operating manual. These operating guidelines support you in making
                                    conscious choices in selecting
                                    opportunities that will naturally advance you in this lifetime. When you use
                                    your natural talents versus learned
                                    talents you gain energy. Maximizing your fuel efficiency allows you to access
                                    your true self and enjoy life in
                                    the process.<br/><br/>

                                    This advanced human assessment curriculum and technology are products of YCG,
                                    LLC dba The YOU Institute. The
                                    curriculum is approved for continuing education by The California State Board of
                                    Behavioral Sciences, The Board
                                    of Registered Nursing and the International Coach Federation. The ULT
                                    Performance Report is helpful to employers
                                    and various agencies seeking compatibility in people placement as well as
                                    professionals trained in relationship
                                    management and psychotherapy. Your ULT Performance Report adds intrinsic value
                                    in fortifying relationships,
                                    seeking a career, preparing for marriage, selecting a roommate and in better
                                    understanding yourself and others.

                                    <h2 class="mt-4" style="color: #f2661c">The ULT Performance Report addresses the
                                        following:</h2>

                                    <ul class="text-white">
                                        <li> Your unique natural expression of self</li>

                                        <li>Talents that motivate and prompt you to participate in life</li>

                                        <li> What you can tolerate in terms of people, places and things</li>

                                        <li>How you connect, learn and commit experiences to memory</li>

                                        <li>Your perception of life that defines your physical reality</li>

                                        <li> How much energy you currently have available to succeed</li>
                                    </ul>

                                </div>

                                <h2 class="mt-4" style="color: #f2661c;text-align: justify">YOUR TRAITS</h2>
                                <p style="text-align: justify">Your natural physical "TRAITS" determine
                                    how nature shows up in you. These traits assist in providing unique insight into
                                    your capabilities and natural talents.</p>

                                @foreach($reports['style_code_details'] as $report)
                                    <h2 class="mt-4"
                                        style="color: #f2661c; text-align: justify">{{$report['public_name']}}</h2>
                                    <p class="text-white" style="text-align: justify">{{$report['text']}}</p>
                                @endforeach

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOUR MOTIVATION</h2>
                                <p class="text-white" style="text-align: justify"> Your "MOTIVATION" addresses what
                                    “DRIVES” you, what must be fed and honored so that you
                                    can successfully reach your destination. There are 12 “DRIVERS” in everyone’s
                                    “vehicle of self”. These
                                    drivers are all chattering at the same time, but only some are licensed to
                                    drive. Knowing how to keep these
                                    legally authorized drivers in the front seat and motivated allows for efficient
                                    travel. These driving forces
                                    represent specific laws of nature that show up in all living things. These
                                    drivers express themselves as
                                    strengths and weaknesses. It is your personal responsibility to come from a
                                    place of strength. Strength
                                    transmits intelligence while weakness produces ignorance. Choosing opportunities
                                    that feed your strengths,
                                    your talents, and your passion, will bring you closer to states of intelligence.
                                    What motivates or drives
                                    you requires you choose those listed below in order of proficiency.</p>

                                @foreach($reports['feature_code_details'] as $report)
                                    <h2 class="mt-4"
                                        style="color: #f2661c; text-align: justify">{{$report['public_name']}}</h2>
                                    <p class="text-white" style="text-align: justify">{{$report['text']}}</p>
                                @endforeach

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOUR BOUNDARIES</h2>
                                <p class="text-white" style="text-align: justify"> “ALCHEMY” addresses your refinement
                                    preferences; whether you are meticulous, practical,
                                    messy, and what you can tolerate in others. The Knowledge of Y.O.U. uses the
                                    analogy of ore to exemplify
                                    states of refinement, specifically Gold, Silver and Copper. Alchemy determines
                                    where your "BOUNDARIES" begin
                                    and end. This range identifies what you can tolerate in terms of people, places
                                    and things and how to best
                                    manage your choices in maximizing your energy potential. Alchemical
                                    incompatibility is the number one reason
                                    for challenges in relationships. Not addressing boundary issues in any
                                    relationship can result in
                                    relationship failure. In business and in life it is vital to know what your
                                    personal alchemical range of
                                    tolerance is so that you can better understand your own boundaries and those
                                    around you.</p>

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOU HAVE A
                                    "{{$reports['alchemy_code_details']['public_name']}}"</h2>
                                @if($reports['alchemy_code_details']['image'] !== null && $reports['alchemy_code_details']['image'] !== 'null')
                                    <div class="mt-4" style="border: 0px solid #ccc;">
                                        <img
                                            src="{{asset('assets/'.$reports['alchemy_code_details']['image'])}}"
                                            style="background:#351a0d; padding: 0px; max-width: 500px;border-radius: 5px"/>
                                    </div>
                                @endif
                                <p class="text-white mt-4"
                                   style="text-align: justify">{{$reports['alchemy_code_details']['text']}}</p>

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOUR COMMUNICATION
                                    STYLE</h2>
                                <p class="text-white" style="text-align: justify">“ENERGY CENTERS” define your
                                    "COMMUNICATION STYLE" and they determine how you uniquely
                                    relate, connect and learn from your environment. They are responsible for how
                                    every individual commits
                                    information and experiences to memory. There are four centers: Intellectual,
                                    Moving, Emotional and
                                    Instinctual. Your pronounced center of energy largely determines how you
                                    initially connect with the moment.
                                    Everyone is different, and knowing this information can be vital in
                                    communicating and connecting effectively
                                    with the world in which we live. The centers are listed below from most
                                    prominent to least prominent in you.
                                    They exemplify the doors to your house of self. The first door represents the
                                    front door. Opening this door
                                    is essential in initiating the process of accessing those that follow. When all
                                    are open, a memory is
                                    established.</p>

                                @foreach($reports['communication_code_details'] as $key => $report)
                                    @if($key == 0)
                                        <h2 class="slider-padding"
                                            style="color: #f2661c; text-align: justify">YOU are
                                            primarily {{$report['public_name']}} centered</h2>
                                    @elseif($key == 1)
                                        <h2 class="slider-padding"
                                            style="color: #f2661c; text-align: justify">YOU are
                                            secondly {{$report['public_name']}} centered</h2>
                                    @elseif($key == 2)
                                        <h2 class="slider-padding"
                                            style="color: #f2661c; text-align: justify">YOU are
                                            thirdly {{$report['public_name']}} centered</h2>
                                    @else
                                        <h2 class="slider-padding"
                                            style="color: #f2661c; text-align: justify">YOU are
                                            lastly {{$report['public_name']}} centered</h2>
                                    @endif
                                    <p class="text-white" style="text-align: justify">{{$report['text']}}</p>
                                @endforeach

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOUR PERCEPTION OF LIFE</h2>
                                <p class="text-white mt-4"
                                   style="text-align: justify">{{$reports['perception_life']['text']}}</p>

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">{{$reports['polarity_code_detail']['public_name']}}</h2>
                                <p class="text-white mt-4"
                                   style="text-align: justify">{{$reports['polarity_code_detail']['text']}}</p>

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOUR ENERGY POOL</h2>
                                <p class="text-white mt-4" style="text-align: justify">Your “ENERGY POOL” represents how much physical energy you have to expend on a daily
                                    basis. Much like staying hydrated, you must guard its appropriation of use. Some activities, choices,
                                    people, places and things can rob you of vital energy, and depending upon the nature of those things or
                                    choices, you may or may not be able to recoup the energy. Throughout life your goal is to maintain an
                                    average or better volume of energy. This makes life more manageable and keeps you from being vulnerable to
                                    toxic abuse. The need for fortifying your presentation can be met by living a more naturally suited
                                    life.</p>

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">{{$reports['energy_code_detail']['public_name']}}</h2>
                                <p class="text-white mt-4"
                                   style="text-align: justify; padding-bottom: 20px; border-bottom: 2px solid #f2661c">{{$reports['energy_code_detail']['text']}}</p>

                                <div style="text-align: justify" class="text-white mt-4"
                                     style="text-align: justify">
                                    The results documented in your Performance Report address the uniqueness and perfection of YOU. We call this Report
                                    Your Operating Manual.<br/><br/>


                                    Your physicality, or physical traits, reveal your natural talents and capabilities which determine your innate
                                    potential. They reveal what drives you and how to fuel this drive; how your personal preferences impact your
                                    boundaries of tolerance for people and places; how you specifically connect with your environment, relate to others
                                    and download information and how your unique perception of life has an electromagnetic impact on all of your
                                    relationships.<br/><br/>


                                    Your Operating Manual identifies what is required for you to maintain a healthy state. Because the Ultimate Life
                                    Tool technology is so multifaceted and multidimensional, to maximize the value of your Operating Manual or if you
                                    have questions about your results, we invite you to contact the certified practitioner below for a personal
                                    consultation.<br/><br/>


                                    Your personal consultation will identify areas that need and require your focus and attention in order to reach your
                                    full potential on a daily basis. It will also provide you with specific strategies to help you integrate and apply
                                    the information from your Operating Manual in every aspect of your life.<br/><br/>


                                    For services and certification contact your ULT assessment provider.

                                </div>

                                <p class="text-white mt-4" style="text-align: justify; padding-bottom: 20px; border-bottom: 2px solid #f2661c">Your practitioner is {{\Illuminate\Support\Facades\Auth::user()['first_name']}} {{\Illuminate\Support\Facades\Auth::user()['last_name']}} <br>{{\Illuminate\Support\Facades\Auth::user()['email']}}</p>
                                <p class="text-white mt-4" style="text-align: justify">For internal use only. <br>Compatibility values for BR {{\Illuminate\Support\Facades\Auth::user()['gender'] == 1 ? '(F)' : '(M)'}} Interval</p>
                                <p class="text-white mt-4" style="text-align: justify">S {{$style_position}}</p>
                                <p class="text-white mt-4" style="text-align: justify">F {{$feature_position}}</p>
                                <p class="text-white mt-4" style="text-align: justify">Alch {{$alchl_code}}</p>
                                <p class="text-white mt-4" style="text-align: justify">PV {{$reports['pv'] > 0 ? '+' : ''}} {{$reports['pv']}}  REP ARC {{$reports['pv'] - $reports['ep']}} to +{{$reports['pv'] + $reports['ep']}}</p>
                                <p class="text-white mt-4" style="text-align: justify">REP {{$reports['ep']}}</p>
                                <p class="text-white mt-4" style="text-align: justify">TEP {{$reports['ep'] * 2}}</p>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>
</div>
</body>
</html>
