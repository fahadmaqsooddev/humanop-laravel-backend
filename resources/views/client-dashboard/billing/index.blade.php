@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'account'])

@section('content')
    <div class="container-fluid my-3 py-3">
        <div class="row">
            <div class="col-lg-8 mt-4">
                <div class="row">
                    <div class="col-xl-8 mb-xl-0 mb-4">
                        <div class="card bg-transparent shadow-xl">
                            <div class="overflow-hidden position-relative border-radius-xl" style="background-image: url('assets/img/curved-images/curved14.jpg');">
                                <span class="mask bg-gradient-dark"></span>
                                <div class="card-body position-relative z-index-1 p-3">
                                    <i class="fas fa-wifi text-white p-2"></i>
                                    <h5 class="text-white mt-4 pb-2">****&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp;&nbsp;{{$card ? $card['last4'] : '****'}}</h5>
                                    <div class="d-flex">
                                        <div class="d-flex">
{{--                                            <div class="me-4">--}}
{{--                                                <p class="text-white text-sm opacity-8 mb-0">Card Holder</p>--}}
{{--                                                <h6 class="text-white mb-0">Jack Peterson</h6>--}}
{{--                                            </div>--}}
                                            <div>
                                                <p class="text-white text-sm opacity-8 mb-0">Expires</p>
                                                <h6 class="text-white mb-0">{{$card ? $card['exp_month'] : '**'}}/{{$card ? $card['exp_year'] : '****'}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-3">
                                        <div class="d-flex">
                                            <div>
                                                <p class="text-white text-sm opacity-8 mb-0">LAST USED</p>
                                                <h6 class="text-white mb-0">{{$card ? \Illuminate\Support\Carbon::parse($user['updated_at'])->format('m/d/Y') : '**/**/****'}}</h6>
                                            </div>
                                        </div>
                                        <div class="ms-auto w-20 d-flex align-items-end justify-content-end">
                                            <img class="w-60 mt-2" src="{{ URL::asset('assets/img/logos/mastercard.png') }}" alt="logo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 200px">
        <div class="col-12">
            <div id="globe" class="position-absolute end-0 top-2 mt-sm-3 me-lg-7" style="z-index: -1">
                <canvas width="700" height="600" class="w-lg-100 h-lg-100 w-75 h-75 me-lg-0 me-n10 mt-lg-5"></canvas>
            </div>
        </div>
    </div>
@endsection
@push('javascript')

@endpush
