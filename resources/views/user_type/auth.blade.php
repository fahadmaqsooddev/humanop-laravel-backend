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
            {{--        @elseif (\App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('intro-assessment'))--}}
            {{--            @include('layouts.navbars.guest.nav')--}}
            {{--            @yield('content')--}}
            {{--            @include('layouts/footers/guest/footer')--}}
        @elseif (\Request::is('client/stripe-checkout'))
            @include('layouts.navbars.guest.nav')
            @yield('content')
            @include('layouts/footers/guest/footer')
        @elseif (\Request::is('client/play'))
            @include('layouts.navbars.guest.nav')
            @yield('content')
            @include('layouts/footers/guest/footer')
            {{--        @elseif (\App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('play'))--}}
            {{--            @include('layouts.navbars.guest.nav')--}}
            {{--            @yield('content')--}}
            {{--            @include('layouts/footers/guest/footer')--}}
        @else
            @include('layouts/navbars/auth/sidebar')
            <main
                class="main-content max-height-vh-100 h-100 {{ (Request::is('ecommerce-products-new-product')||$childFolder == 'profile' ? 'position-relative' : (Request::is('pages-rtl') ? 'position-relative border-radius-lg overflow-hidden' : 'position-relative border-radius-lg')) }}">

            @if (\Request::is('pages-rtl'))
                @include('layouts/navbars/auth/nav-rtl')
            @else
                @include('layouts/navbars/auth/nav')
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
                                        <h5 style="color: white; padding: 0 0 5px 0;">Thank you for being a Beta Tester!  Give us some feedback below on how your
                                            experience is so far and how we can improve it.</h5>
                                        <form action="javascript:void(0);">

                                            <div class="p-2" id="feedback_success_message" hidden>
                                                <span class="text-success">Thank you for your feedback! We have given
                                                    you a point as a token of our appreciation!</span>
                                            </div>

                                            <textarea id="comment-value" rows="5" class="comment-box" placeholder="Add a Comment..." required></textarea>
                                            <button type="submit" onclick="submitFeedBackForm()" class="btn" style="inline-size: 100%;background-color: #f2661c;color: white;">Submit Feedback</button>

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

        if (open_modal === "true") {

            $(window).on('load', function () { // on page change the modal populates

                $('#add_feedback').click();

                localStorage.setItem('modal_open_time', false); // after showing modal value turns to false

                console.log('aaa');
            });

        }

        if (open_modal !== 'false') {

            var now = new Date();

            var modal_open_time = now.setMinutes(now.getMinutes() + 5); // add 5 minutes in login time to open modal

            var local_storage_time = localStorage.getItem('modal_open_time');

            if (local_storage_time === null) {

                console.log('Set');

                localStorage.setItem('modal_open_time', modal_open_time);

                local_storage_time = localStorage.getItem('modal_open_time');
            }

            let intervalID = setInterval(function () {

                var now_date_minute = Math.floor(new Date().getMinutes());

                var local_storage_date_minute = Math.floor(new Date(parseInt(local_storage_time)).getMinutes());

                if (local_storage_date_minute === now_date_minute) {

                    console.log('RUN');

                    localStorage.setItem('modal_open_time', true);

                    clearInterval(intervalID);

                    intervalID = null;

                }

            }, 60000);

        }
    }

    function submitFeedBackForm() {

        $.ajax({
            url: '{{ route("user-feedback") }}',
            method: 'POST',
            data: {'comment': $('#comment-value').val()},
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            },
            success: function (response) {

                $('#feedback_success_message').removeAttr('hidden');

                animateNumber(1);

                setTimeout(function (){
                    $('#add_feedback').click();
                }, 2000);
            },
            error: function (response) {
                $('#add_feedback').click();
                console.log(response);
            }
        });
    }
</script>
