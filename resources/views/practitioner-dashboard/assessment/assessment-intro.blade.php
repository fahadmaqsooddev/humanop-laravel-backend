@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
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
                               href="{{ Auth::check() ? (request()->segment(1) === 'client' ? url('client/dashboard') : url('practitioner/dashboard')) : url('login') }}">
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
                                       style="padding: 10px 16px 10px 16px; border-radius: 7px;margin-left: 14px; font-size: 16px !important;"
                                       class="connection-btn w-25 text-center">Proceed
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
                                    <a href="{{ url('practitioner/play') }}"
                                       style="padding: 10px 16px 10px 16px; border-radius: 7px;margin-left: 14px; font-size: 16px !important;"
                                       class="connection-btn w-25 text-center text-white">Proceed
                                    </a>
                                    <a href="{{ route('admin_dashboard') }}"
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
    @if(\App\Helpers\Helpers::getWebUser()['timezone'] == null || '')
        <div class="modal fade" id="timeZoneModel" tabindex="-1"
             role="dialog"
             aria-labelledby="couponModel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <label class="form-label fs-4 text-white">Time Zone</label>

                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                            <form method="post" action="{{route('admin_set_timezone')}}" class="mb-4">
                                @csrf
                                <div class="card-body pt-0">
                                    <label class="form-label" style="color: #f2661c; font-size: 18px">Timezone</label>
                                    <div class="form-group">
                                        <select class="form-control text-color-dark" style="background-color: #1c365e; color: white" name="timezone">
                                            @foreach($timezones as $timezone)
                                                <option value="{{$timezone}}">{{$timezone}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn-sm float-end mt-2 mb-0" style="background-color: #f2661c; color: white; font-size: 14px">
                                        set timezone
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <button class="btn btn-primary d-none"  data-bs-toggle="modal" data-bs-target="#timeZoneModel" id="open-time-zone-modal">
    </button>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#open-time-zone-modal').trigger('click');
        });
    </script>
@endpush
