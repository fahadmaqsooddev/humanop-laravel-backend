@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <main class="main-content mt-0" style="background-color: #eaf3ff">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg">
            <div class="col-lg-12 position-relative z-index-2">
                <div class="mb-4 mt-4">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex flex-column h-100" style="align-items: center">
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
                <div class="row" style="justify-content: center">
                    <div class="col-lg-5 col-sm-5 mt-3">
                        <a href="https://maestro-dev.humanoptech.com/session?email={{ urlencode(Illuminate\Support\Facades\Session::get('login_user_email')) }}&password={{ urlencode(Illuminate\Support\Facades\Session::get('login_user_password')) }}" target="_blank">
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
                <div class="row" style="justify-content: center">
                    <div class="col-lg-5 col-sm-5 mt-3">
                        <a href="https://dev-hai.humanoptech.com/session?email={{ urlencode(Illuminate\Support\Facades\Session::get('login_user_email')) }}&password={{ urlencode(Illuminate\Support\Facades\Session::get('login_user_password')) }}" target="_blank">
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
            </div>
        </div>
    </main>
@endsection

<script>
    localStorage.removeItem('modal_open_time'); // when user logs out then feedback modal value turns to false
</script>
<script>
    // Make sure the DOM is fully loaded before running the script
    document.addEventListener('DOMContentLoaded', function () {
        // Select the toggle button and the input field
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('password-eye');

        // Check if elements exist before adding event listener
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function (e) {
                e.preventDefault();


                // Toggle password visibility
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye'); // Switch to eye-slash icon
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash'); // Switch back to eye icon
                }
            });
        }
    });


</script>
