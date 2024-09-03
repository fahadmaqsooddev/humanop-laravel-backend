@extends('user_type.auth', ['parentFolder' => 'client-dashboard ', 'childFolder' => 'none'])
@section('content')
    <div class="page-header position-relative m-3 border-radius-xl">
        <img src="{{ URL::asset('assets/img/login.webp') }}" alt="pattern-lines" class="position-absolute opacity-6 start-0 top-0 w-100">
        <div class="container pb-lg-9 pb-10 pt-4 postion-relative z-index-2">
            <div class="row">
                <div class="col-md-6 mx-auto text-center">
                    <h3 class="text-white">See our pricing</h3>
                    <p class="text-white">You have Free Unlimited Updates and Premium Support on each package.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-n6">
        <div class="container">
            <div class="tab-content tab-space">
                <div class="tab-pane active" id="monthly">
                    <div class="row">
                        <div class="col-lg-4 mb-lg-0 mb-4">
                            <div class="card">
                                <div class="card-header text-center pt-4 pb-3">
                                    <span class="badge rounded-pill bg-gradient text-dark">Freemium</span>
                                    <h1 class="font-weight-bold mt-2 text-white">
                                        <small>Free</small>
                                    </h1>
                                </div>
                                <div class="card-body text-lg-start text-center pt-0">
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">1 Assessment every 90 days</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Daily Tip</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">1 Action Item</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Basic Results</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <i class="fas fa-minus"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Action Plan</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <i class="fas fa-minus"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Training Strategies</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <i class="fas fa-minus"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Renewal System</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <i class="fas fa-minus"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Early Releases</span>
                                        </div>
                                    </div>
                                    <a href="{{route('stripe_checkout')}}" class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0">
                                        Join
                                        <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-lg-0 mb-4">
                            <div class="card">
                                <div class="card-header text-center pt-4 pb-3">
                                    <span class="badge rounded-pill bg-gradient text-dark">Core</span>
                                    <h1 class="font-weight-bold mt-2 text-white">
                                        <small>$</small>10
                                    </h1>
                                </div>
                                <div class="card-body text-lg-start text-center pt-0">
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">1 Assessment every 90 days</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Multiple Tips</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">3 Action Items</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Detailed Results</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <i class="fas fa-minus"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Action Plan</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <i class="fas fa-minus"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Training Strategies</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <i class="fas fa-minus"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Renewal System</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                                            <i class="fas fa-minus"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Early Releases</span>
                                        </div>
                                    </div>
                                    <a href="{{route('stripe_checkout')}}" class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0">
                                        join
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
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Licensing Model</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Multiple Daily Tips</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">HAI Feature</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Gamification</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Training Strategies</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Renewal System</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Action Plan</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-start justify-content-center p-2">
                                        <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                                            <i class="fas fa-check opacity-10"></i>
                                        </div>
                                        <div>
                                            <span class="ps-3">Early Releases</span>
                                        </div>
                                    </div>
                                    <a href="{{route('stripe_checkout')}}" class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0">
                                        Join
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

@endsection
