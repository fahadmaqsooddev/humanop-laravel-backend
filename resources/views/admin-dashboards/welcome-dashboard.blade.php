@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <main class="main-content" style="background-color: #eaf3ff">
            <div class="col-lg-12 position-relative z-index-2">
                <div class="">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex flex-column h-100 mt-5" style="align-items: center">
                                    <img src="{{ URL::asset('assets/img/new_logo_dark.png') }}"
                                         style="width: 15%"
                                         alt="main_logo">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex flex-column h-100" style="align-items: center">
                                    <h1 class="font-weight-bolder custom-text-dark mb-0">
                                        Welcome to the HumanOp <br> Super Admin Dashboard
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex flex-column h-100 text-center">
                                    <h5 class="font-weight-bolder custom-text-dark mb-0">
                                        You have permission to access other dashboards. Feel free to move between them
                                        with ease.
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @canany(['user_management', 'assessment_management', 'technology_management', 'team_management', 'cms_admin', 'support_admin', 'client_queries', 'approve_queries'])
                    <div class="row" style="justify-content: center">
                        <div class="col-lg-5 col-sm-5 mt-6">
                            <a href="{{ route('admin_dashboard') }}" target="_blank">
                                <div class="card mb-4" style="border: 2px solid #1b3a62">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-md mb-0 text-capitalize font-weight-bold"
                                                       style="color: #1B3A62">B2C Admin Dashboard</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endcanany
                @canany(['organizations', 'support', 'role_template_manage', 'invites', 'pricing_plan', 'b2b_support_admin', 'coupons'])
                    <div class="row" style="justify-content: center">
                        <div class="col-lg-5 col-sm-5 mt-3">
                            <a href="https://maestro-dev.humanoptech.com/session?email={{ urlencode(Illuminate\Support\Facades\Session::get('login_user_email')) }}&password={{ urlencode(Illuminate\Support\Facades\Session::get('login_user_password')) }}"
                               target="_blank">
                                <div class="card mb-4" style="border: 2px solid #1b3a62">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-md mb-0 text-capitalize font-weight-bold"
                                                       style="color: #1B3A62">B2B Admin Dashboard</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endcanany
                @canany(['persona', 'brains', 'knowledge', 'advance', 'comparison'])
                    <div class="row" style="justify-content: center">
                        <div class="col-lg-5 col-sm-5 mt-3">
                            <a href="https://dev-hai.humanoptech.com/session?email={{ urlencode(Illuminate\Support\Facades\Session::get('login_user_email')) }}&password={{ urlencode(Illuminate\Support\Facades\Session::get('login_user_password')) }}"
                               target="_blank">
                                <div class="card mb-4" style="border: 2px solid #1b3a62">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-md mb-0 text-capitalize font-weight-bold"
                                                       style="color: #1B3A62">HAi Admin Dashboard</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endcanany
                <div class="row" style="justify-content: center">
                    <div class="col-lg-5 col-sm-5 mt-3" style="display: flex; justify-content: center">
                        <a href="{{ url('/logout')}}" style="color: white; background-color: #1b3a62;" class="btn btn-sm float-end mt-2 mb-0">Logout</a>
                    </div>
                </div>
            </div>
    </main>
@endsection

