@extends('app')

@section('auth')

    <style>
        .feedback-card {
            color: white;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            padding: 0 25px 25px 25px;
            text-align: center;
        }

        .feedback-card h2 {
            margin-block-end: 15px;
            color: #333333;
        }

        .feedback-card p {
            color: #585758;
            font-size: 15px;
            margin-block-end: 30px;
        }

        .comment-box {
            inline-size: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #dddddd;
            border-radius: 8px;
            margin-block-end: 20px;
            resize: none;
            font-size: 14px;
            font-family: inherit;
            outline: none;
        }

        /* Alert box container */
        .custom-alert {
            background-color: #f8d7da; /* Light red background */
            color: #721c24; /* Dark red text */
            border: 1px solid #f5c6cb; /* Matching border color */
            border-radius: 5px; /* Rounded corners */
            padding: 15px 20px; /* Space inside the box */
            margin: 10px 0; /* Space around the alert */
            font-family: Arial, sans-serif; /* Font styling */
            font-size: 1rem;
        }

        /* Alert heading styling */
        .custom-alert strong {
            font-weight: bold;
        }


        /* Alert box container */
        .custom-30-minute-alert {
            background-color: #f8d7da; /* Light red background */
            color: #721c24; /* Dark red text */
            border: 1px solid #f5c6cb; /* Matching border color */
            border-radius: 5px; /* Rounded corners */
            padding: 15px 20px; /* Space inside the box */
            margin: 10px 0; /* Space around the alert */
            font-family: Arial, sans-serif; /* Font styling */
            font-size: 1rem;
        }

        /* Alert heading styling */
        .custom-30-minute-alert strong {
            font-weight: bold;
        }
    </style>

    @if ($parentFolder == 'authentication')
        @if ($navbar == 'basic')
            @include('layouts/navbars/auth/nav-auth-basic')
        @else
        @endif
        <main class="main-content max-height-vh-100 h-100">
            @yield('content')
            @if ($hasFooter == 'footer')
                @include('layouts/footers/guest/footer')
            @endif
        </main>
    @else
        @if(\Request::is('email-verify'))
            @include('layouts.navbars.guest.nav')
            @yield('content')
            @include('layouts.footers.guest.footer')
        @endif
        @if (\Request::is('dashboard-virtual-default')||Request::is('dashboard-virtual-info'))
            <div>
                @include('layouts/navbars/auth/nav')
            </div>
            <div class="virtual-reality">
                <div class="border-radius-xl mt-3 mx-3 position-relative"
                     style="background-image: url('assets/img/vr-bg.jpg') ; background-size: cover;">
                    @include('layouts/navbars/auth/sidebar')
                    @yield('content')
                </div>
                @include('layouts/footers/auth/footer')
            </div>
        @elseif (\Request::is('client/intro-assessment'))
            @include('layouts.navbars.guest.nav')
            @yield('content')
            @include('layouts/footers/guest/footer')
            {{--        @elseif (\Request::is('client/new-dashboard'))--}}
            {{--            @include('layouts.navbars.auth.new-sidebar')--}}

            {{--            @yield('content')--}}
            {{--            @include('layouts/footers/guest/footer')--}}
        @elseif (\Request::is('practitioner/intro-assessment'))
            @include('layouts.navbars.guest.nav')
            @yield('content')
            @include('layouts/footers/guest/footer')
        @elseif (\Request::is('client/version'))
            @include('layouts.navbars.guest.nav')
            @yield('content')
            @include('layouts/footers/guest/footer')
        @elseif (\Request::is('practitioner/play'))
            @include('layouts.navbars.guest.nav')
            @yield('content')
            @include('layouts/footers/guest/footer')
        @elseif (request()->segment(3) === 'intro-assessment')
            @include('layouts.navbars.guest.nav')
            @yield('content')
            @include('layouts/footers/guest/footer')
        @elseif (\Request::is('client/stripe-checkout'))
            @include('layouts.navbars.guest.nav')
            @yield('content')
            @include('layouts/footers/guest/footer')
        @elseif (\Request::is('client/play'))
            @include('layouts.navbars.guest.nav')
            @yield('content')
            @include('layouts/footers/guest/footer')
        @elseif (request()->segment(3) === 'play')
            @include('layouts.navbars.guest.nav')
            @yield('content')
            @include('layouts/footers/guest/footer')

        @else
            @if(\Request::is('client/new-dashboard'))
                @include('layouts/navbars/auth/new-sidebar')
            @else
                @include('layouts/navbars/auth/sidebar')
            @endif
            <main
                class="main-content max-height-vh-100 h-100 {{ (Request::is('ecommerce-products-new-product')||$childFolder == 'profile' ? 'position-relative' : (Request::is('pages-rtl') ? 'position-relative  overflow-hidden' : 'position-relative ')) }}">

            @if (\Request::is('pages-rtl'))
                @include('layouts/navbars/auth/nav-rtl')
            @else
                @if(!(\Request::is('client/new-dashboard')))
                    @include('layouts/navbars/auth/nav')
                @endif
            @endif
            @if($childFolder == 'profile'||$childFolder == 'account'||Request::is('ecommerce-products-new-product'))
                @yield('content')
            @else

                @if($parentFolder === 'client-dashboard')
                    @include('client-dashboard/chat-ai/chat-ai-modal')
                @endif
                <!-- added px-0 class -->
                    <div class="container-fluid pt-2 pb-2 px-0">

                        <button type="button" data-bs-toggle="modal"
                                data-bs-target="#feedBackModal"
                                id="add_feedback"
                                class="btn btn-sm updateBtn mt-2 mb-0" hidden>Add
                        </button>
                        <button type="button" data-bs-toggle="modal"
                                data-bs-target="#afterThirtyMinsFeedBackModal"
                                id="add_feedback_after_thirty_mins"
                                class="btn btn-sm updateBtn mt-2 mb-0" hidden>Add
                        </button>


                        <!-- Modal -->
                        <div class="modal fade" id="feedBackModal" tabindex="-1"
                             role="dialog"
                             aria-labelledby="feedBackModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="d-flex justify-content-end p-3">
                                        <a type="button" class="close modal-close-btn text-white"
                                           data-bs-dismiss="modal"
                                           aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </a>
                                    </div>

                                    <div class="feedback-card">
                                        <div id="customAlert" class="custom-alert" style="display: none">
                                            <strong>Error!</strong> <span id="custom_error_message"> </span>
                                        </div>
                                        <h5 style="color: white; padding: 0 0 5px 0; display: flex; text-align: justify">
                                            Thanks for being a Beta Tester! In the last 3 logins, do you have any
                                            constructive feedback of what’s working well or what could be improved?</h5>
                                        <form action="javascript:void(0);">

                                            <div class="p-2" id="feedback_success_message" hidden>
                                                <span class="text-success">Thank you for submitting your feedback! We will credit you a point after we verify your feedback as a token of our appreciation!</span>
                                            </div>

                                            <textarea id="comment-value" rows="5" class="comment-box"
                                                      placeholder="Add a Comment..." required></textarea>
                                            <a type="submit" onclick="submitFeedBackForm()" class="btn"
                                               style="inline-size: 100%;background-color: #f2661c;color: white;">
                                                Submit Feedback
                                            </a>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- After 30 mins feedback Modal -->
                        <div class="modal fade" id="afterThirtyMinsFeedBackModal" tabindex="-1"
                             role="dialog"
                             aria-labelledby="feedBackModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="d-flex justify-content-end p-3">
                                        <a type="button" class="close modal-close-btn text-white"
                                           data-bs-dismiss="modal"
                                           aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </a>
                                    </div>
                                    <div class="feedback-card">
                                        <div id="customThirtyMinuteAlert" class="custom-30-minute-alert"
                                             style="display: none">
                                            <strong>Error!</strong> <span id="custom_30_minute_error_message"> </span>
                                        </div>
                                        <h5 style="color: white; padding: 0 0 5px 0; display: flex; text-align: justify">
                                            Thanks for being a Beta Tester! Wow! You’ve been on the app for over 30
                                            minutes! Would love to hear some constructive feedback as to why you’ve been
                                            on this long…what do you love about it…or are you stuck? Let us know!</h5>
                                        <form action="javascript:void(0);">

                                            <div class="p-2" id="feedback_success" hidden>
                                                <span class="text-success">Thank you for submitting your feedback! We will credit you a point after we verify your feedback as a token of our appreciation!</span>
                                            </div>

                                            <textarea id="feedback-comment-value" rows="5" class="comment-box"
                                                      placeholder="Add a Comment..." required></textarea>
                                            <button type="submit" onclick="submitAfterThirtyFeedBackForm()" class="btn"
                                                    style="inline-size: 100%;background-color: #f2661c;color: white;">
                                                Submit Feedback
                                            </button>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @yield('content')
                        @include('layouts/footers/auth/footer')
                    </div>
                @endif
            </main>
        @endif
        @include('components/fixed-plugins')
    @endif
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script>

        var is_admin = {{\App\Helpers\Helpers::getWebUser()->is_admin ?? 0}};
        var feedback_exists = "{{\App\Helpers\Helpers::getWebUser()->feedback()->exists() ?? ""}}";
        var is_feedback = {{\App\Helpers\Helpers::getWebUser()->is_feedback ?? 3}};

        console.log(is_admin, feedback_exists, is_feedback);

        if (is_admin === 2 && !feedback_exists) { // check if user is client then show feedback pop up

            open_modal = localStorage.getItem('modal_open_time');

            if (open_modal === null && is_feedback == 1) {

                $(window).on('load', function () { // on page change the modal populates

                    $('#add_feedback').click();

                    localStorage.setItem('modal_open_time', false); // after showing modal value turns to false

                    console.log('aaa');
                });

            }


            if (open_modal !== 'true') {

                var now = new Date();

                var modal_open_time = now.setMinutes(now.getMinutes() + 30); // add 30 minutes in login time to open modal

                var local_storage_time = localStorage.getItem('modal_open_time');

                // if (local_storage_time === null) {

                console.log('Set');

                localStorage.setItem('modal_open_time', modal_open_time);

                local_storage_time = localStorage.getItem('modal_open_time');
                // }

                let intervalID = setInterval(function () {

                    var now_date_minute = Math.floor(new Date().getMinutes());

                    var local_storage_date_minute = Math.floor(new Date(parseInt(local_storage_time)).getMinutes());

                    if (local_storage_date_minute === now_date_minute) {

                        console.log('RUN');

                        $('#add_feedback_after_thirty_mins').click();

                        localStorage.setItem('modal_open_time', true);

                        clearInterval(intervalID);

                        intervalID = null;

                    }

                }, 60000);

            }
        }

</script>
