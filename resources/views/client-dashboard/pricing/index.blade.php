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
    .top-heading{
        margin-top:100px;
    }
    .bg-pricing{
        height:100vh;
    }
</style>
@section('content')
<div class="page-header position-relative m-3 border-radius-xl ">
        <img src="{{ URL::asset('assets/img/login.webp') }}" alt="pattern-lines" class="position-absolute opacity-6 start-0 top-0 w-100 bg-pricing" >
        <div class="container pb-md-2 pb-4 pt-5 pt-md-1 top-heading ">
            <div class="row">
                <div class="col-md-6 mx-auto   text-center">
                    <h3 class="text-white">See our pricing</h3>
                    <p class="text-white">You have Free Unlimited Updates and Premium Support on each package.</p>
                </div>
            </div>
        </div>

    </div>

    <div class="">
        <div class="container">
            <div class="tab-content tab-space">
                <div class="tab-pane active" id="monthly">
                    <div class="row d-flex justify-content-center">
                    <div class="col-lg-4 col-md-8 col-sm-10 mb-lg-0 mb-4 ">
                            <div class="card">
                                <div class="card-header text-center pt-4 pb-3">
                                    <span class="badge rounded-pill bg-gradient text-dark">Core</span>
                                    <h1 class="font-weight-bold mt-2 text-white">
                                        <small>$</small>10
                                    </h1>
                                </div>
                                <div class="card-body text-lg-start text-center pt-0">
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/assessmentIcon.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">1 Assessment every 90 days</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/multiple tips.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Multiple Tips</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/3 action item.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">3 Action Items</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/Detailed Results.png')}}"
                                                 style="width: 15px; margin-top: 8px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Detailed Results</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <img src="{{asset('assets/icons/action plan.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Action Plan</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <img src="{{asset('assets/icons/training strategies.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Training Strategies</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <img src="{{asset('assets/icons/Renewal System.png')}}"
                                                 style="width: 12px; margin-top: 3px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Renewal System</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <img src="{{asset('assets/icons/Early Releases.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Early Releases</span>
                                        </div>
                                    </div>
                                    <a href="{{route('stripe_checkout')}}"
                                       class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0" data-bs-toggle="modal"
                                       data-bs-target="#subcriptionModel">
                                        Update Membership
                                        <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-lg-0 mb-4">
                            <div class="card">
                                <div class="card-header text-center pt-4 pb-3">
                                    <span class="badge rounded-pill bg-gradient text-dark">Freemium</span>
                                    <h1 class="font-weight-bold mt-2 text-white">
                                        <small>Free</small>
                                    </h1>
                                </div>
                                <div class="card-body text-lg-start text-center pt-0">
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/assessmentIcon.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">1 Assessment every 90 days</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/tips.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Daily Tip</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/1 action item.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">1 Action Item</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/Basic results only.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Basic Results</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <img src="{{asset('assets/icons/action plan.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Action Plan</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <img src="{{asset('assets/icons/training strategies.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Training Strategies</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <img src="{{asset('assets/icons/Renewal System.png')}}"
                                                 style="width: 12px; margin-top: 3px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Renewal System</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <img src="{{asset('assets/icons/Early Releases.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Early Releases</span>
                                        </div>
                                    </div>
                                    <a href="{{route('stripe_checkout')}}"
                                       class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0" data-bs-toggle="modal"
                                       data-bs-target="#subcriptionModel">
                                        Free Membership
                                        <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-lg-0 mb-4">
                            <div class="card">
                                <div class="card-header text-center pt-4 pb-3">
                                    <span class="badge rounded-pill bg-gradient text-dark">Premium</span>
                                    <h1 class="font-weight-bold mt-2 text-white">
                                        <small>$</small>50
                                    </h1>
                                </div>
                                <div class="card-body text-lg-start text-center pt-0">
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/action plan.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Licensing Model</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/multiple tips.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Multiple Daily Tips</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/HAI Feature.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">HAI Feature</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/Gamification.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Gamification</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/training strategies.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Training Strategies</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/Renewal System.png')}}"
                                                 style="width: 12px; margin-top: 3px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Renewal System</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/action plan.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Action Plan</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start p-2">
                                        <div
                                            class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <img src="{{asset('assets/icons/Early Releases.png')}}"
                                                 style="width: 15px; margin-top: 5px">
                                        </div>
                                        <div>
                                            <span class="ps-3">Early Releases</span>
                                        </div>
                                    </div>
                                    <a href="{{route('stripe_checkout')}}"
                                       class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0" data-bs-toggle="modal"
                                       data-bs-target="#subcriptionModel">
                                        Update Membership
                                        <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="subcriptionModel" tabindex="-1" role="dialog"
         aria-labelledby="subcriptionModel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fs-4 text-white">Payment</label>
                                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
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
                                                       size='2' value="{{$user['pm_exp_month'] ? $user['pm_exp_month'] : ''}}"
                                                       name="expMonth" id="expMonth"
                                                       style="background-color: #0F1535; color: white; border-radius: 15px;">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="expYear" class="text-white">Expiration Year</label>
                                                <input type="text" class="form-control card-expiry-year" placeholder='YYYY'
                                                       maxlength="4"
                                                       size='4' value="{{$user['pm_exp_year'] ? $user['pm_exp_year'] : ''}}"
                                                       name="expYear" id="expYear"
                                                       style="background-color: #0F1535; color: white; border-radius: 15px;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn w-100 my-4 mb-2" id="discount_amount"
                                                style="background-color: #f2661c;color:white">Pay Now
                                            ($10)
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

@endsection
