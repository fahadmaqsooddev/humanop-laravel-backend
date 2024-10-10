@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])
<style>
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

    .top-heading {
        margin-top: 100px;
    }

    .bg-pricing {
        height: 100vh;
    }
    .container {
        margin-left: 0px !important;
        padding-left: 0px !important;
    }
    .navbar{
        padding-left: 0px !important;
    }
    .navbar-brand{
        width: 280px;
    }
    .body-background {
        background-color:  #1C365E !important;
    }
</style>
@section('content')



    <div class="mt-5 pt-5">
        <div class="mt-5">
            <div class="tab-content tab-space mt-5">
                <div class="tab-pane active" id="monthly">
                    <div class="row w-80" style="margin-left: 20%">
                        <div class="col-7">
                            <h3 class="text-bold text-white">Welcome to the HumanOp Assessment</h3>
                            <h4 class="text-white text-bold">"Creating Optimal Relationships in Business, Home &
                                Life"</h4>
                            <p class="text-white">Our evolutionary human assessment technology is designed to help you
                                gain deeper insights into yourself. Its analysis will help you understand your natural
                                abilities, behaviors, strengths, preferences, perceptions and the unique way you connect
                                with the world around you. Taking the HumanOp assessment is a great opportunity for you
                                will learn how to use your natural talents in such a way that is sustainable, and keeps
                                you energized and optimized. The HumanOp technology stands apart by offering actionable,
                                physics-based insights that guide you to your highest performance potential.</p>
                            <p class="text-white">Before you take the HumanOp Assessment make sure that you are NOT
                                multi-tasking and ideally in a quiet environment where you can focus on being present to
                                the following questions.</p>
                            <p class="text-white">The questions are simple and do not require extensive thought. It
                                should take you no longer than 15-minutes to complete.</p>
                            @if(\App\Helpers\Helpers::getWebUser()['practitioner_id'] != null)
                                <a href="{{\App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('play')}}" style="padding: 10px 16px 10px 16px; border-radius: 7px"
                                   class="rainbow-border-assessment-intro-btn w-25 text-center">Proceed
                                </a>
                            @else
                                <a href="{{url('client/play')}}" style="padding: 10px 16px 10px 16px; border-radius: 7px"
                                   class="rainbow-border-assessment-intro-btn w-25 text-center">Proceed
                                </a>
                            @endif
                        </div>
                        <div class="col-5" style="margin-top: 40%">
                            <img  style="width:90%;height: 90%" src="{{asset('assets/img/icons/assessment_intro_icon.png')}}">
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
@endsection
