@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')
    <div class="row">
        <div class="col-lg-12 position-relative z-index-2">
            <div class="container-fluid">
                <section>
                    <div class="row mt-lg-4 mt-2">
                        <div class="col-12">
                            <div class="card" style="text-align: center">
                                <div class="card-body p-3 ">
                                    <div style="border: 0px solid #ccc;"><img src="{{asset('assets/img/ultlogo.png')}}" style="background:#351a0d; padding: 0px; max-width: 500px;border-radius: 5px"/></div>
                                    <div class="text-white">“Advanced Human Assessment Technology for a Better Mankind”</div>
                                    <h1 class="text-white">ULT Summary Report</h1>
                                    <h4 class="text-white">{{$user['first_name']}} {{$user['last_name']}}, {{$user['gender'] == 0 ? 'Male' : 'Female'}}, Interval</h4>
                                    <div class="text-white mt-4" style="text-align: justify">
                                        The ULT Performance Report serves to identify those aspects about you that define and direct your best
                                        performance qualities. Since your physical being is respectively the assigned vehicle transporting you through
                                        this lifetime, it's often helpful to know what kind of vehicle you are. The Greeks have been insisting we "Know
                                        Thyself" for centuries. This simple request answered can facilitate success in all aspects of life including
                                        one's performance in conducting business and creating healthy relationships at work and in life. The ULT
                                        technology is a patented instrument registered and branded as The Ultimate Life Tool. The methodology serving as
                                        the foundation for its development is referred to as The Knowledge of Y.O.U. (your own understanding). This
                                        cumulative insight is older than the language of man and is founded in physical law and scientific objective
                                        understanding. The ULT assessment tool queries and quantifies information and identifies results in a manner
                                        that can be easily understood. Your personal ULT Performance Report introduces you to Y.O.U. and provides you
                                        with your own operating manual. These operating guidelines support you in making conscious choices in selecting
                                        opportunities that will naturally advance you in this lifetime. When you use your natural talents versus learned
                                        talents you gain energy. Maximizing your fuel efficiency allows you to access your true self and enjoy life in
                                        the process.<br/><br/>

                                        This advanced human assessment curriculum and technology are products of YCG, LLC dba The YOU Institute. The
                                        curriculum is approved for continuing education by The California State Board of Behavioral Sciences, The Board
                                        of Registered Nursing and the International Coach Federation. The ULT Performance Report is helpful to employers
                                        and various agencies seeking compatibility in people placement as well as professionals trained in relationship
                                        management and psychotherapy. Your ULT Performance Report adds intrinsic value in fortifying relationships,
                                        seeking a career, preparing for marriage, selecting a roommate and in better understanding yourself and others.

                                        <h2 class="mt-4" style="color: #f2661c">The ULT Performance Report addresses the following:</h2>

                                        <ul class="text-white">
                                            <li> Your unique natural expression of self</li>

                                            <li>Talents that motivate and prompt you to participate in life</li>

                                            <li> What you can tolerate in terms of people, places and things</li>

                                            <li>How you connect, learn and commit experiences to memory</li>

                                            <li>Your perception of life that defines your physical reality</li>

                                            <li> How much energy you currently have available to succeed</li>
                                        </ul>

                                    </div>

                                    <h2 class="mt-4" style="color: #f2661c; text-align: start">YOUR TRAITS</h2>
                                    <p style="color:#f2661c; text-align: start">Your natural physical "TRAITS" determine how nature shows up in you. These traits assist in providing unique insight into your capabilities and natural talents.</p>

                                    @foreach($reports as $report)
                                        <h2 class="mt-4" style="color: #f2661c; text-align: start">{{$report[0]['public_name']}}</h2>
                                        <p class="text-white" style="text-align: start">{{$report[0]['text']}}</p>
                                    @endforeach

                                    <h2 class="mt-4" style="color: #f2661c; text-align: start">YOUR MOTIVATION</h2>
                                    <p class="text-white" style="text-align: start"> Your "MOTIVATION" addresses what “DRIVES” you, what must be fed and honored so that you
                                            can successfully reach your destination. There are 12 “DRIVERS” in everyone’s “vehicle of self”. These
                                            drivers are all chattering at the same time, but only some are licensed to drive. Knowing how to keep these
                                            legally authorized drivers in the front seat and motivated allows for efficient travel. These driving forces
                                            represent specific laws of nature that show up in all living things. These drivers express themselves as
                                            strengths and weaknesses. It is your personal responsibility to come from a place of strength. Strength
                                            transmits intelligence while weakness produces ignorance. Choosing opportunities that feed your strengths,
                                            your talents, and your passion, will bring you closer to states of intelligence. What motivates or drives
                                            you requires you choose those listed below in order of proficiency.</p>
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

