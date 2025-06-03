@extends('user_type.guest', ['parentFolder' => 'session', 'childFolder' => 'none'])

@section('content')
    <main class="main-content mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg">
            <div class="container">
                <div class="row d-flex flex-column justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-6 col-lg-6 col-md-7 mx-auto">
                    <div class="card z-index-0 left-nav-blue-color">

                        @if($errors->any())

                            <div class="m-3  alert alert-warning alert-dismissible fade show text-center" role="alert">

                                @if(count($errors->messages()) > 1)

                                    @foreach($errors->messages() as $err)

                                        <span class="alert-text text-white">
                                    {{$err[0]}}</span>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close">
                                            <i class="fa fa-close" aria-hidden="true"></i>
                                        </button>
                                        <br>

                                    @endforeach

                                @else
                                    <span class="alert-text text-white">
                                    {{$errors->first()}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </button>
                                @endif
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success"
                                 role="alert">
                        <span class="alert-text text-white">
                            {{ session('success') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                        <div class="card-header pb-0 text-center">
                            <h4 class="mb-0" style="color: #0f1535">Change password</h4>
                        </div>
                        <div class="card-body">
                            <form role="form" action="/reset-password" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="mt-3 position-relative">
                                    <div>
                                        <label for="password" style="color: #0f1535; font-size: 15px">Password</label>
                                        <input type="password" class="form-control" placeholder="Password"
                                               aria-label="Password" aria-describedby="password-addon" maxlength="30"
                                               name="password"
                                               id="password"
                                               style="background-color: #f3deba; color: black; border-radius: 15px;">
                                        <!-- Eye icon for toggling password visibility -->
                                        <span class="position-absolute" id="togglePassword"
                                              style="right: 15px; top: 50px; transform: translateY(-50%); cursor: pointer; color: white; z-index: 10;">
                                        <i class="fas fa-eye-slash pt-1 password-eye" id="password-eye"
                                           style="    color: #1b3a62 !important;"></i>
                                    </span>
                                    </div>
                                </div>
                                <div class="mt-3 position-relative">
                                    <div>
                                        <label for="password" style="color: #0f1535; font-size: 15px">Confirm
                                            Password</label>
                                        <input type="password" class="form-control"
                                               placeholder="Confirm Password"
                                               aria-label="Password" aria-describedby="password-addon" maxlength="30"
                                               name="password_confirmation"
                                               id="confirmPassword"
                                               style="background-color: #f3deba; color: black; border-radius: 15px;">
                                        <!-- Eye icon for toggling password visibility -->
                                        <span class="position-absolute" id="toggleConfirmPassword"
                                              style="right: 15px; top: 50px; transform: translateY(-50%); cursor: pointer; color: white; z-index: 10;">
                                        <i class="fas fa-eye-slash pt-1 confirm-password-eye" id="confirm-password-eye"
                                           style="    color: #1b3a62 !important;"></i>
                                    </span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn w-100 mt-4 mb-0 text-white"
                                            style="background-color: #1b3a62 !important;">Recover your password
                                    </button>
                                    <div class="mt-3" style="color: #0f1535"> Already have an account? <a
                                            style="color: #0f1535" href="{{url('login')}}"
                                            class="font-weight-bolder">Sign in</a></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
<script>
    // Make sure the DOM is fully loaded before running the script
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('password-eye');

        // Add click event listener to the eye icon
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default behavior (especially for anchors)

                // Toggle the password visibility and the eye icon
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye'); // Change to the eye-slash icon when password is visible
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash'); // Change back to the eye icon when password is hidden
                }
            });
        }


        // confirm password eye toggler
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const confirmIcon = document.getElementById('confirm-password-eye');

        // Add click event listener to the eye icon
        if (toggleConfirmPassword && confirmPasswordInput) {
            toggleConfirmPassword.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default behavior (especially for anchors)

                // Toggle the password visibility and the eye icon
                if (confirmPasswordInput.type === 'password') {
                    confirmPasswordInput.type = 'text';
                    confirmIcon.classList.remove('fa-eye-slash');
                    confirmIcon.classList.add('fa-eye'); // Change to the eye-slash icon when password is visible
                } else {
                    confirmPasswordInput.type = 'password';
                    confirmIcon.classList.remove('fa-eye');
                    confirmIcon.classList.add('fa-eye-slash'); // Change back to the eye icon when password is hidden
                }
            });
        }
    });
</script>
