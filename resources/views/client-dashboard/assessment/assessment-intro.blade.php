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

    <div class="page-header position-relative m-3 border-radius-xl" style="height: 250px">
        <img src="{{ URL::asset('assets/img/login.webp') }}" alt="pattern-lines" class="position-absolute opacity-6 start-0 top-0 w-100 bg-pricing" >
        <div class="container pb-md-2 pb-4 pt-5 pt-md-1 top-heading ">
            <div class="row">
                <div class="col-md-6 mx-auto text-center">
                    <h3 class="text-white">The HumanOp Assessment</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="">
        <div class="container">
            <div class="tab-content tab-space">
                <div class="tab-pane active" id="monthly">
                    <div class="row d-flex justify-content-center">
                        <h2 class="primaryColor text-bold mt-4">Welcome to the HumanOp Assessment (Powered by The ULT)</h2>
                        <h4 class="text-white text-bold">"Creating Perfect Optimal Relationships in Business, Home & Life" </h4>
                        <p class="mt-4 text-white">This evolutionary Human Assessment Instrument enables professionals and individuals to identify personal potential, human tolerance, perceptivity, motivations and behavioral strengths & weaknesses. Taking the test is a great opportunity for individuals seeking self-awareness to better understand themselves and others. The technology is unique and is considered to be the most objective and first tool of choice for those professionals seeking honest answers for their clients and themselves.</p>

                    </div>
                    <a href="{{route('test_play')}}" style="padding: 10px 16px 10px 16px; border-radius: 7px; background-color: #f2661c"
                       class="text-white btn btn-sm-1 btn-md-3 btn-lg-5 ">Proceed
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
