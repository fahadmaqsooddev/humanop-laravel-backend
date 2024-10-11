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
    .ml-50{
        margin-left: 50px !important;
    }
    .response-width{
        width: 100% !important;
    }
    @media (min-width: 0px) and (max-width: 767px) {
            .response-width{
                width: 90% !important;
            }
    }
    @media (min-width: 767px) and (max-width: 991px) {
        .response-width{
            width: 94% !important;
        }
    }

    .text-color-blue{
        color: #1c365e !important;
    }
</style>
@section('content')
<div class="page-header position-relative m-3 border-radius-xl ">
{{--        <img src="{{ URL::asset('assets/img/login.webp') }}" alt="pattern-lines" class="position-absolute opacity-6 start-0 top-0 w-100 bg-pricing" >--}}
        <div class="container pb-md-2 pb-4 pt-5 pt-md-1 mt-5 ">
            <div class="row">
                <div class="col-md-8 mx-auto   text-center">
                    <h3 class="text-black">See our pricing</h3>
                    <p class="text-black">You have Free Unlimited Updates and Premium Support on each package.</p>
                </div>
            </div>
        </div>

    </div>

    <div class="">
        <div class="container">
            <div class="tab-content tab-space">
                <div class="tab-pane active" id="monthly">
                    <div class="row d-flex justify-content-center">

                        @foreach($plans as $plan)

                        <div class="col-lg-4 mb-lg-0 mb-4">
                            @if($plan->name != 'Freemium')
                                <div class="text-center">
                                <button class="rainbow-border-user-nav-btn btn-icon d-lg-block   response-width "  >
                                    Coming Soon !
                                </button>
                                </div>
                                <div class="card {{$plan->name == 'Premium' ? 'pricing-premium-card mt-2' : 'pricing-core-card mt-2' }}" style="height: 665px;">

                                    <div class="card-header text-center pt-4 pb-3">
                                        <span class="badge rounded-pill bg-gradient  {{$plan->name == 'Core' ? 'text-color-blue' : 'text-dark'}}">{{$plan->name}}</span>
                                        <h1 class="font-weight-bold mt-2 {{$plan->name == 'Core' ? 'text-color-blue' : 'text-white'}}">
                                            <small>{{$plan->price == "0.00" ? "Free" : "$" . (int)$plan->price }}</small>
                                        </h1>
                                    </div>
                                    @else
                                        <div class="card pricing-freemium-card" style="margin-top: 57px !important;">

                                            <div class="card-header text-center pt-4 pb-3">
                                                <span class="badge rounded-pill bg-gradient text-dark {{$plan->name == 'Core' ? 'text-color-blue' : ''}}">{{$plan->name}}</span>
                                                <h1 class="font-weight-bold mt-2  {{$plan->name == 'Core' ? 'text-color-blue' : 'text-white'}}">
                                                    <small>{{$plan->price == "0.00" ? "Free" : "$" . (int)$plan->price }}</small>
                                                </h1>
                                            </div>
                                            @endif


                                @if($plan->name == 'Freemium')

                                <div class="card-body text-lg-start  pt-0">

                                        <div class="row pb-1">
                                            <div class="col-2">
                                            </div>
                                            <div class="col-10">
                                                <b class="text-white">Everything in Freemium</b>
                                            </div>
                                        </div>
                                    <div class="row pb-1">
                                        <div class="col-2">
                                            <div
                                                class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                                <img src="{{asset('assets/icons/assessmentIcon.png')}}"
                                                     style="width: 15px; margin-top: 5px">
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <p class="text-white text-sm">Assessment every 90 days</p>
                                        </div>
                                    </div>



                                    <div class="row pb-1">
                                        <div class="col-2">
                                            <div
                                                class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                                <img src="{{asset('assets/icons/tips.png')}}"
                                                     style="width: 15px; margin-top: 5px">
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <p class="text-white text-sm">Basic results with video and transcription</p>
                                        </div>
                                    </div>



                                    <div class="row pb-1">
                                        <div class="col-2">
                                            <div
                                                class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                                <img src="{{asset('assets/icons/1 action item.png')}}"
                                                     style="width: 15px; margin-top: 5px">
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <p class="text-white text-sm">1 daily tip based on 1 element</p>
                                        </div>
                                    </div>



                                    <div class="row pb-1">
                                        <div class="col-2">
                                            <div
                                                class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                                <img src="{{asset('assets/icons/Basic results only.png')}}"
                                                     style="width: 15px; margin-top: 5px">
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <p class="text-white text-sm">Limited 90-day action plan (1 action item)</p>
                                        </div>
                                    </div>


                                    <div class="row pb-1">
                                        <div class="col-2">
                                            <div
                                                class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                                <img src="{{asset('assets/icons/action plan.png')}}"
                                                     style="width: 15px; margin-top: 5px">
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <p class="text-white text-sm">HAi companion</p>
                                        </div>
                                    </div>

                                    <div class="row pb-1">
                                        <div class="col-2">
                                            <div
                                                class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                                <img src="{{asset('assets/icons/training strategies.png')}}"
                                                     style="width: 15px; margin-top: 5px">
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <p class="text-white text-sm">Cannot download/export content</p>
                                        </div>
                                    </div>



                                    <div class="row pb-1">
                                        <div class="col-2">
                                            <div
                                                class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                                <img src="{{asset('assets/icons/Renewal System.png')}}"
                                                     style="width: 12px; margin-top: 3px">
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <p class="text-white text-sm">Unlock additional content with points or referrals</p>
                                        </div>
                                    </div>


                                    <div class="row pb-1">
                                        <div class="col-2">
                                            <div
                                                class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                                <img src="{{asset('assets/icons/Early Releases.png')}}"
                                                     style="width: 15px; margin-top: 5px">
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <p class="text-white text-sm">Earn free premium access through referrals</p>
                                        </div>
                                    </div>

                                    @if($user->plan_name == "Freemium")
                                        <div class="text-center">
                                        <a class="rainbow-border-user-nav-btn btn-icon d-lg-block mt-3 mb-0">
                                            Current Membership
                                            <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                            </div>
                                    @else
                                        <div class="text-center">
                                        <a href="{{route('stripe_checkout')}}"
                                           class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0" data-bs-toggle="modal"
                                           data-bs-target="#subcriptionModel">
                                            Free Membership
                                            <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                        </div>
                                    @endif
                                </div>

                                @elseif($plan->name == "Core") {{-- Core --}}

                                    <div class="card-body text-lg-start  pt-0">
                                        <div class="row pb-1">
                                            <div class="col-2">
                                            </div>
                                            <div class="col-10">
                                                <b class="text-color-blue ml-2">Everything in FREEMIUM, plus:</b>
                                            </div>
                                        </div>


                                        <div class="row pb-1">
                                            <div class="col-2">
                                                <div
                                                    class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                                    <img src="{{asset('assets/icons/assessmentIcon.png')}}"
                                                         style="width: 15px; margin-top: 5px">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <p class="text-color-blue text-sm">Full 90-day action plan (3 action items)</p>
                                            </div>
                                        </div>




                                        <div class="row pb-1">
                                            <div class="col-2">
                                                <div
                                                    class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                                    <img src="{{asset('assets/icons/multiple tips.png')}}"
                                                         style="width: 15px; margin-top: 5px">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <p class="text-color-blue text-sm">Multiple daily tips (based on 2 elements)</p>
                                            </div>
                                        </div>


                                        <div class="row pb-1">
                                            <div class="col-2">
                                                <div
                                                    class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center"
                                                    >
                                                    <img src="{{asset('assets/icons/3 action item.png')}}"
                                                         style="width: 14px; margin-top: 5px">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <p class="text-color-blue text-sm">HAi companion with memory function (remembers
                                                    conversations)</p>
                                            </div>
                                        </div>

                                        <div class="row pb-1">
                                            <div class="col-2">
                                                <div
                                                    class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                                    <img src="{{asset('assets/icons/Detailed Results.png')}}"
                                                         style="width: 15px; margin-top: 8px">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <p class="text-color-blue text-sm">Quarterly assessments</p>
                                            </div>
                                        </div>



                                        <div class="row pb-1">
                                            <div class="col-2">
                                                <div
                                                    class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                                    <img src="{{asset('assets/icons/action plan.png')}}"
                                                         style="width: 15px; margin-top: 5px">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <p class="text-color-blue text-sm">Focus on successful strategies</p>
                                            </div>
                                        </div>



                                        <div class="row pb-1">
                                            <div class="col-2">
                                                <div
                                                    class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                                    <img src="{{asset('assets/icons/training strategies.png')}}"
                                                         style="width: 15px; margin-top: 5px">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <p class="text-color-blue text-sm">Earn free premium access through referrals</p>
                                            </div>
                                        </div>





{{--                                        @if($user->plan_name == "Core")--}}
{{--                                            <a class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0">--}}
{{--                                                Current Membership--}}
{{--                                                <i class="fas fa-arrow-right ms-1"></i>--}}
{{--                                            </a>--}}
{{--                                        @else--}}
{{--                                            <a class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0"--}}
{{--                                               data-bs-toggle="modal" data-bs-target="#subcriptionModel{{$plan->name}}">--}}
{{--                                                Update Membership--}}
{{--                                                <i class="fas fa-arrow-right ms-1"></i>--}}
{{--                                            </a>--}}
{{--                                        @endif--}}
                                    </div>


                                @elseif($plan->name == "Premium") {{-- Premium --}}

                                    <div class="card-body text-lg-start pt-0">
                                        <div class="row pb-1">
                                            <div class="col-2">
                                            </div>
                                            <div class="col-10">
                                                <b class="text-white">Everything in CORE, plus:</b>
                                            </div>
                                        </div>

                                        <div class="row pb-1">
                                            <div class="col-2">
                                                <div
                                                    class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                                    <img src="{{asset('assets/icons/action plan.png')}}"
                                                         style="width: 15px; margin-top: 5px">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <p class="text-white">Deeper training strategies</p>
                                            </div>
                                        </div>


                                        <div class="row pb-1">
                                            <div class="col-2">
                                                <div
                                                    class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                                    <img src="{{asset('assets/icons/multiple tips.png')}}"
                                                         style="width: 15px; margin-top: 5px">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <p class="text-white">Full 90-day action plan with weekly guidance</p>
                                            </div>
                                        </div>



                                        <div class="row pb-1">
                                            <div class="col-2">
                                                <div
                                                    class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center"
                                                   >
                                                    <img src="{{asset('assets/icons/HAI Feature.png')}}"
                                                         style="width: 15px; margin-top: 5px">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <p class="text-white">3 daily tips (morning, noon, and night) based on
                                                    top traits</p>
                                            </div>
                                        </div>


                                        <div class="row pb-1">
                                            <div class="col-2">
                                                <div
                                                    class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                                    <img src="{{asset('assets/icons/Gamification.png')}}"
                                                         style="width: 15px; margin-top: 5px">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <p class="text-white">Early access to beta releases</p>
                                            </div>
                                        </div>




                                        <div class="row pb-1">
                                            <div class="col-2">
                                                <div
                                                    class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                                    <img src="{{asset('assets/icons/training strategies.png')}}"
                                                         style="width: 15px; margin-top: 5px">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <p class="text-white">Full access to training and resources</p>
                                            </div>
                                        </div>





                                        <div class="row pb-1">
                                            <div class="col-2">
                                                <div
                                                    class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center"
                                              >
                                                    <img src="{{asset('assets/icons/Renewal System.png')}}"
                                                         style="width: 12px; margin-top: 3px">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <p class="text-white">HAi companion for strategy, feedback, and
                                                    network building</p>
                                            </div>
                                        </div>


{{--                                        @if($user->plan_name == "Premium")--}}
{{--                                            <a class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0">--}}
{{--                                                Current Membership--}}
{{--                                                <i class="fas fa-arrow-right ms-1"></i>--}}
{{--                                            </a>--}}
{{--                                        @else--}}
{{--                                            <a class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0"--}}
{{--                                               data-bs-toggle="modal" data-bs-target="#subcriptionModel{{$plan->name}}">--}}
{{--                                                Update Membership--}}
{{--                                                <i class="fas fa-arrow-right ms-1"></i>--}}
{{--                                            </a>--}}
{{--                                        @endif--}}
                                    </div>

                                @endif
                            </div>
                        </div>

                            @if($plan->name != 'Freemium') {{-- modals --}}

                                <div class="modal fade" id="subcriptionModel{{$plan->name}}" tabindex="-1" role="dialog"
                                     aria-labelledby="subcriptionModel{{$plan->name}}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body" style=" border-radius: 9px">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <label class="form-label fs-4 text-white">Payment</label>
                                                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <div class="error d-none">
                                                                <div class="m-3  alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                                        <i class="fa fa-close" aria-hidden="true"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <form role="form" action="{{route('process_payment')}}" method="post"
                                                                  class="require-validation mt-4"
                                                                  data-cc-on-file="false"
                                                                  data-stripe-publishable-key="{{ $stripe_setting['public_key'] }}" id="payment-form">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <label for="cardNumber" class="text-white">Card Number</label>
                                                                    <input autocomplete='off' maxlength="16" size='16'
                                                                           class="form-control card-number"
                                                                           placeholder="Enter You Card Number"
                                                                           name="cardNumber" id="cardNumber"
                                                                           value="{{$user['pm_last_four'] ? '************'.$user['pm_last_four'] : ''}}"
                                                                           style="background-color: #0F1535; color: white; border-radius: 15px;" required>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="mb-3">
                                                                            <label for="cvc" class="text-white">CVC</label>
                                                                            <input placeholder='ex. 311' maxlength="3" size='4' type="text"
                                                                                   class="form-control card-cvc" aria-label="Password"
                                                                                   name="cvc" id="cvc"
                                                                                   style="background-color: #0F1535; color: white; border-radius: 15px;" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="mb-3">
                                                                            <label for="expMonth" class="text-white">Expiration Month</label>
                                                                            <input type="text" class="form-control card-expiry-month" placeholder='MM'
                                                                                   maxlength="2"
                                                                                   size='2' value="{{$user['pm_exp_month'] ? $user['pm_exp_month'] : ''}}"
                                                                                   name="expMonth" id="expMonth"
                                                                                   style="background-color: #0F1535; color: white; border-radius: 15px;" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="mb-3">
                                                                            <label for="expYear" class="text-white">Expiration Year</label>
                                                                            <input type="text" class="form-control card-expiry-year" placeholder='YYYY'
                                                                                   maxlength="4"
                                                                                   size='4' value="{{$user['pm_exp_year'] ? $user['pm_exp_year'] : ''}}"
                                                                                   name="expYear" id="expYear"
                                                                                   style="background-color: #0F1535; color: white; border-radius: 15px;" required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="text" name="plan_id" value="{{$plan->plan_id}}" hidden>

                                                                <div class="text-center">
                                                                    <button type="submit" class="rainbow-border-user-nav-btn w-100 my-4 mb-2" id="submit_button"
                                                                            >Pay Now
                                                                        ({{(int)$plan->price}})
                                                                    </button>
                                                                </div>

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script type="text/javascript">

        $(function () {

            /*------------------------------------------
            --------------------------------------------
            Stripe Payment Code
            --------------------------------------------
            --------------------------------------------*/

            var $form = $(".require-validation");

            $('form.require-validation').bind('submit', function (e) {

                $('#submit_button').addClass('disabled');

                var $form = $(".require-validation"),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'].join(', '),
                    $inputs = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid = true;
                $errorMessage.addClass('hide');

                $('.has-error').removeClass('has-error');
                $inputs.each(function (i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('hide');
                        e.preventDefault();
                    }
                });

                var cardNumber = $('.card-number').val();
                var cardCVC = $('.card-cvc').val();
                var cardExpiryMonth = $('.card-expiry-month').val();
                var cardExpiryYear = $('.card-expiry-year').val();

                var storedCardNumber = '************' + '{{ $user['pm_last_four'] }}';
                var firstTwelveDigits = storedCardNumber.substr(0, 12);

                if (cardNumber.substr(0, 12) !== firstTwelveDigits) {
                    if (!$form.data('cc-on-file')) {
                        e.preventDefault();
                        Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                        Stripe.createToken({
                            number: cardNumber,
                            cvc: cardCVC,
                            exp_month: cardExpiryMonth,
                            exp_year: cardExpiryYear
                        }, stripeResponseHandler);
                    }
                }


            });

            /*------------------------------------------
            --------------------------------------------
            Stripe Response Handler
            --------------------------------------------
            --------------------------------------------*/
            function stripeResponseHandler(status, response) {

                if (response.error) {

                    console.log(status, response.error);

                    $('#submit_button').removeClass('disabled');

                    $('.error')
                        .removeClass('d-none')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    /* token contains id, last4, and card type */
                    var token = response['id'];

                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
            }

        });
    </script>
@endpush
