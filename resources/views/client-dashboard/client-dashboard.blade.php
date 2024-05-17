@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')
    <div class="container-fluid">
        <div class="page-header min-height-100 border-radius-xl">
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ URL::asset('assets/img/bruce-mars.jpg') }}" alt="profile_image"
                             class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1 text-white">
                            Mark Johnson
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm text-white">
                            Optimal Trait To Be In Right Now:
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active " data-bs-toggle="tab" href="javascript:;"
                                   role="tab" aria-selected="true">
                                    <span class="ms-1">Access Your Results</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" href="javascript:;" role="tab"
                                   aria-selected="false">
                                    <span class="ms-1">Get Free Pro Access!</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <section class="py-3">
            <div class="row mt-lg-4 mt-2">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white" style="color: rgb(160, 174, 192)"> TEAM MEMBER
                                OPTIMIZATION HOTSPOTS</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> John T : Driver Optimization</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Syivia : Space Optimization</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Marcus G : Role Optimization</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Tiana R : Personal Support</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)">etc...</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="card" style="border-radius: 3rem !important;">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white" style="color: rgb(160, 174, 192)"> COMPANY DISCUSSION<br>
                                FEEDS</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Latest Posts:</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> General Discussion - </p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Sales Team -</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> HR - </p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Operations - </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white" style="color: rgb(160, 174, 192)"> TEAM OPTIMIZATION
                                STRATEGIES FOR THE NEXT 90 DAYS</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Summary:</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Optimize Bullpen Seating Based
                                on Alchemy</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Define Director of Sales
                                Role</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Separate Director of Operation
                                into 2 position</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-lg-4 mt-2">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white" style="color: rgb(160, 174, 192)"> TEAM MEMBER
                                OPTIMIZATION HOTSPOTS</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> John T : Driver Optimization</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Syivia : Space Optimization</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Marcus G : Role Optimization</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Tiana R : Personal Support</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)">etc...</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="card" style="border-radius: 3rem !important;">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white" style="color: rgb(160, 174, 192)"> HIP - HumanOp Integration Podcast</p>
                            <div class="card mb-4"
                                 style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <div
                                                    class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                    <i class="fa fa-play text-lg opacity-10" aria-hidden="true"></i>
                                                </div>
                                                <h5 class="font-weight-bolder mb-0">
                                                    <span class="text-success text-sm font-weight-bolder"></span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-4"
                                 style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <div
                                                    class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                    <i class="fa fa-play text-lg opacity-10" aria-hidden="true"></i>
                                                </div>
                                                <h5 class="font-weight-bolder mb-0">
                                                    <span class="text-success text-sm font-weight-bolder"></span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white" style="color: rgb(160, 174, 192)"> TEAM OPTIMIZATION
                                STRATEGIES FOR THE NEXT 90 DAYS</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Summary:</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Optimize Bullpen Seating Based
                                on Alchemy</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Define Director of Sales
                                Role</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Separate Director of Operation
                                into 2 position</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('layouts/footers/auth/footer')
    </div>
@endsection
