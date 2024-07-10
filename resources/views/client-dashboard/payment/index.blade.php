@extends('user_type.auth', ['parentFolder' => 'client-dashboard ', 'childFolder' => 'none'])
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

    .pagination {
        float: right;
        margin-right: 24px;
    }

    .page-link {
        background: none !important;
    }

    .page-link:hover {
        background: #f2661c !important;
        color: white !important;
    }

    .page-item.active .page-link {
        background: #f2661c !important;
        color: white !important;
        border-color: #f2661c !important;
    }

</style>
@section('content')
    <main class="main-content mt-2">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 border-radius-lg"
             style="background-image: url('assets/img/login.webp');">
            {{-- <span class="mask bg-gradient-dark opacity-6"></span> --}}
            <div class="container">
                <div class="row d-flex flex-column justify-content-center">

                </div>
            </div>
        </div>
        <div class="container">
            @include('layouts.message')
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-8 col-lg-5 col-md-4">
                    <div class="card z-index-0">
                        <div class="card-body">
                            <form id="checkCoupon">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <p id="success_message"></p>
                                            <p id="error_message"></p>
                                            <label class="form-label fs-4 text-white">Do you have any Coupon</label>
                                            <div class="form-group mt-4">
                                                <input style="background-color: #0f1534;" class="form-control text-white"
                                                       type="text" name="coupon" maxlength="9"
                                                       placeholder="enter coupon code">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0 mx-2">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card z-index-0 mt-4">
                        <div class="card-body">
                            <p class="text-white mb-2 text-2xl text-bold">Payment Details</p>
                            <form role="form" action="{{route('process_payment')}}" method="post"
                                  class="require-validation"
                                  data-cc-on-file="false"
                                  data-stripe-publishable-key="{{ $stripe['public_key'] }}" id="payment-form">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" class="form-control" hidden name="amount"
                                           value="{{$stripe['amount']}}" id="amount"
                                           style="background-color: #0F1535; color: white; border-radius: 15px;">
                                    <label for="" class="text-white">Name</label>
                                    <input type="text" class="form-control" placeholder="Enter Card Holder Name"
                                           style="background-color: #0F1535; color: white; border-radius: 15px;">

                                </div>
                                <div class="mb-3">
                                    <label for="cardNumber" class="text-white">Card Number</label>
                                    <input autocomplete='off' type="text" maxlength="16" size='16'
                                           class="form-control card-number"
                                           placeholder="Enter You Card Number"
                                           name="cardNumber" id="cardNumber"
                                           style="background-color: #0F1535; color: white; border-radius: 15px;">
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="cvc" class="text-white">CVC</label>
                                            <input placeholder='ex. 311' maxlength="3" size='4' type="text"
                                                   class="form-control card-cvc" aria-label="Password"
                                                   name="cvc" id="cvc"
                                                   style="background-color: #0F1535; color: white; border-radius: 15px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="expMonth" class="text-white">Expiration Month</label>
                                            <input type="text" class="form-control card-expiry-month" placeholder='MM'
                                                   maxlength="2"
                                                   size='2'
                                                   name="expMonth" id="expMonth"
                                                   style="background-color: #0F1535; color: white; border-radius: 15px;">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="expYear" class="text-white">Expiration Year</label>
                                            <input type="text" class="form-control card-expiry-year" placeholder='YYYY'
                                                   maxlength="4"
                                                   size='4'
                                                   name="expYear" id="expYear"
                                                   style="background-color: #0F1535; color: white; border-radius: 15px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn w-100 my-4 mb-2" id="discount_amount"
                                            style="background-color: #f2661c;color:white">Pay Now
                                        (${{$stripe['amount']}})
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script>
        $(document).ready(function () {
            // Set up the AJAX request
            $('#checkCoupon').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: 'https://saas.humanoptech.com/check_coupon',
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {

                        var discountedAmount = response.amount;

                        if (response.status == 200) {
                            $('#discount_amount').text('Pay Now ($' + discountedAmount + ')');
                            $('#amount').val(discountedAmount);

                            $('#success_message').html("<div class='alert alert-success'>" + response.success + "</div>");

                            // Hide success message after 2 seconds
                            setTimeout(function() {
                                $('#success_message').html("");
                            }, 2000);

                        } else if(response.status == 202) {
                            window.location.href = "{{route('test_play')}}";
                        } else {
                            $('#discount_amount').text('Pay Now ($' + discountedAmount + ')');

                            $('#error_message').html("<div class='alert alert-danger'>" + response.error + "</div>");

                            // Hide error message after 2 seconds
                            setTimeout(function() {
                                $('#error_message').html("");
                            }, 2000);
                        }


                    },
                    error: function (response) {

                        console.log(response);


                    }
                });
            });
        });
    </script>

    <script type="text/javascript">

        $(function () {

            /*------------------------------------------
            --------------------------------------------
            Stripe Payment Code
            --------------------------------------------
            --------------------------------------------*/

            var $form = $(".require-validation");

            $('form.require-validation').bind('submit', function (e) {
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

                console.log($form.data('stripe-publishable-key'));
                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }

            });

            /*------------------------------------------
            --------------------------------------------
            Stripe Response Handler
            --------------------------------------------
            --------------------------------------------*/
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
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
