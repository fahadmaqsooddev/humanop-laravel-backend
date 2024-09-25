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
    .top-heading{
        margin-top:100px;
    }
    .bg-pricing{
        height:100vh;
    }
</style>
@section('content')

    <div class="page-header position-relative m-3 border-radius-xl" style="height: 200px">
        <img src="{{ URL::asset('assets/img/login.webp') }}" alt="pattern-lines" class="position-absolute opacity-6 start-0 top-0 w-100 bg-pricing" >
        <div class="container pb-md-2 pb-4 pt-5 pt-md-1 top-heading ">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <h2 class="primaryColor text-bold mt-4">Welcome to the HumanOp Assessment</h2>

                </div>
            </div>
        </div>

    </div>

    <div class="">
        <div class="container">
            <div class="tab-content tab-space">
                <div class="tab-pane active" id="monthly">
                    <div class="row d-flex justify-content-center">
                        <h4 class="mt-3 text-white text-bold">"Creating Optimal Relationships in Business, Home & Life"</h4>
                        <p class="mt-3 text-white">Our evolutionary human assessment technology is designed to help you gain deeper insights into yourself. Its analysis will help you understand your natural abilities, behaviors, strengths, preferences, perceptions and the unique way you connect with the world around you. Taking the HumanOp assessment is a great opportunity for you will learn how to use your natural talents in such a way that is sustainable, and keeps you energized and optimized. The HumanOp technology stands apart by offering actionable, physics-based insights that guide you to your highest performance potential.</p>
                        <p class="mt-3 text-white">Before you take the HumanOp Assessment make sure that you are NOT multi-tasking and ideally in a quiet environment where you can focus on being present to the following questions.</p>
                        <p class="mt-3 text-white">The questions are simple and do not require extensive thought. It should take you no longer than 15-minutes to complete.</p>
                    </div>
                    <a href="{{route('test_play')}}" style="padding: 10px 16px 10px 16px; border-radius: 7px; background-color: #f2661c"
                       class="text-white btn btn-sm-1 btn-md-3 btn-lg-5 ">Proceed
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
