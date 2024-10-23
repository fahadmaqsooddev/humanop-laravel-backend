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
                        <div class="col-lg-7 col-md-7 col-12 mt-2">
                            <a class="navbar-brand d-flex flex-column font-weight-bolder ms-lg-0 ms-3 text-white"
                               href="{{ Auth::check() ? (request()->segment(1) === 'client' ? url('client/dashboard') : \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('dashboard')) : url('login') }}">
                                <img
                                    src="{{ Route::is('client_intro_assessment') ? asset('assets/img/new_logo.png') : asset('assets/img/new_logo.png') }}"
                                    alt=""
                                    style="width: auto; height: 100px">
                            </a>
                            <h3 class="text-bold text-white mt-3 pl-14">Welcome to the HumanOp Assessment</h3>
                            <h4 class="text-white text-bold pl-14">"Creating Optimal Relationships in Business, Home &
                                Life"</h4>
                            <p class="text-white pl-14" style="display: flex; text-align: justify">Our evolutionary
                                human assessment technology is designed to help you
                                gain deeper insights into yourself. Its analysis will help you understand your natural
                                abilities, behaviors, strengths, preferences, perceptions and the unique way you connect
                                with the world around you. Taking the HumanOp assessment is a great opportunity for you
                                will learn how to use your natural talents in such a way that is sustainable, and keeps
                                you energized and optimized. The HumanOp technology stands apart by offering actionable,
                                physics-based insights that guide you to your highest performance potential.</p>
                            <p class="text-white pl-14" style="display: flex; text-align: justify">Before you take the
                                HumanOp Assessment make sure that you are NOT
                                multi-tasking and ideally in a quiet environment where you can focus on being present to
                                the following questions.</p>
                            <p class="text-white pl-14" style="display: flex; text-align: justify">The questions are
                                simple and do not require extensive thought. It
                                should take you no longer than 15-minutes to complete.</p>
                            @if(\App\Helpers\Helpers::getWebUser()['practitioner_id'] != null)
                                <div class="d-flex justify-content-between">
                                    <a href="{{\App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('play')}}"
                                       style="padding: 10px 16px 10px 16px; border-radius: 7px;margin-left: 14px"
                                       class="rainbow-border-assessment-intro-btn w-25 text-center">Proceed
                                    </a>
                                    <a href="{{\App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('dashboard')}}"
                                       style="padding: 10px 0px 10px 16px; border-radius: 7px;margin-left: 14px;color:white;font-weight: bold"
                                       class="text-center">
                                        <i class="fa-solid fa-arrow-left " style="color:#ED7537;"></i> <span
                                            style="color:#ED7537;">Back</span>
                                    </a>
                                </div>
                            @else
                                <div class="d-flex justify-content-between">
                                    <a href="{{url('client/play')}}"
                                       style="padding: 10px 16px 10px 16px; border-radius: 7px;margin-left: 14px"
                                       class="rainbow-border-assessment-intro-btn w-25 text-center text-white">Proceed
                                    </a>
                                    <a href="{{url('login')}}"
                                       class=" text-center"
                                       style="padding: 10px 0px 10px 16px; border-radius: 7px;margin-left: 14px;color:white;font-weight: bold"><i
                                            class="fa-solid fa-arrow-left" style="color:#ED7537;"></i> <span
                                            style="color:#ED7537;">Back</span>
                                    </a>
                                </div>
                            @endif
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
@push('js')
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script>
        const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
            searchable: true,
            fixedHeight: true
        });
    </script>
@endpush
