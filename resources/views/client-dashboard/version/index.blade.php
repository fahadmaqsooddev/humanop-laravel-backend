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
        display: none !important;
    }

    .navbar-brand {
        width: 280px;
    }

    .body-background {
        background-color: #1C365E !important;
    }

    .pl-14 {
        padding-left: 14px !important;
    }

    @media (min-width: 390px) and (max-width: 768px) {
        .introAssessmentMargin {
            padding-left: 10%;
        }

        .introAssessmentLogo {
            margin-top: 0px;
        }
    }


    @media (min-width: 768px) and (max-width: 2560px) {
        .introAssessmentMargin {
            padding-left: 10%;
        }

        .introAssessmentLogo {
            padding-top: 35%;
        }

    }
</style>
@section('content')

    <div>
        <div>
            <div class="tab-content tab-space ">
                <div class="tab-pane active" id="monthly">
                    <div class="row w-100 introAssessmentMargin">
                        <div class="col-lg-7 col-md-7 col-12 mt-2 text-white">
                            <a class="navbar-brand d-flex flex-column font-weight-bolder ms-lg-0 ms-3 text-white"
                               href="{{ Auth::check() ? (request()->segment(1) === 'client' ? url('client/dashboard') : \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('dashboard')) : url('login') }}">
                                <img
                                    src="{{ Route::is('client_intro_assessment') ? asset('assets/img/new_logo.png') : asset('assets/img/new_logo.png') }}"
                                    alt=""
                                    style="width: auto; height: 100px">
                            </a>
                            <h4 class="text-white text-bold pl-14">{{$version['version']}}</h4>
                            <p class="text-white pl-14" style="display: flex; text-align: justify">{!! $version['details'] !!}</p>
                        </div>
                        <div class="col-lg-5 col-md-5 col-12 introAssessmentLogo pl-14">
                            <img style="width:90%;height: 90%"
                                 src="{{asset('assets/img/icons/assessment_intro_icon.png')}}">
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>

@endsection

