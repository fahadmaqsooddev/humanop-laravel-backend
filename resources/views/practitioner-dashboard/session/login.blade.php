@extends('user_type.guest', ['parentFolder' => 'session', 'childFolder' => 'none'])

@section('content')
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
             style="background-image: url('{{ url('assets/img/login.webp') }}');">
            <div class="container">
                <div class="row d-flex flex-column justify-content-center">
                    <div class="col-lg-7 text-center mx-auto">
                        <h1 class="text-white mb-2 mt-5">Welcome to the Practitioner Login Form</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                    <div class="card z-index-0">

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
                        <div class="card-header text-center pt-4">
                            <h5 class="text-white">Login With</h5>
                        </div>
                        <div class="css-mgpqwz d-flex justify-content-center">
                            <div>
                                <a href="{{ url('auth/google') }}">
                                    <button class="btn btn-primary bg-light p-2 border border-radius-lg border-gray-800"
                                            type="button"
                                            style="background-color: rgb(19, 21, 54);">
                                        <img src="https://www.svgrepo.com/show/303108/google-icon-logo.svg" alt=""
                                             width="30px" ;>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <p class="text-center text-white"><b>or</b></p>
                        <div class="card-body">
                            <form role="form" class="text-start"  method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="text-white">Email</label>
                                    <input type="email" class="form-control" placeholder="Email" aria-label="Email"
                                           name="email" id="email"
                                           @if(isset($_COOKIE['email'])) value="{{ $_COOKIE['email'] }}" @endif
                                           style="background-color: #0F1535; color: white; border-radius: 15px;">
                                </div>
                                <div class="mb-1 position-relative">
                                    <label for="password" class="text-white">Password</label>
                                    <input type="password" class="form-control" placeholder="Password"
                                           aria-label="Password"
                                           @if(isset($_COOKIE['password'])) value="{{ $_COOKIE['password'] }}" @endif
                                           name="password" id="password"
                                           style="background-color: #0F1535; color: white; border-radius: 15px; padding-right: 40px;">

                                    <!-- Make sure the eye icon is positioned correctly -->
                                    <a class="position-absolute" id="togglePassword"
                                       style="right: 15px; top: 65%; transform: translateY(-50%); cursor: pointer; color: white; z-index: 20;">
                                        <i class="fas fa-eye pt-1 password-eye" id="password-eye" style="    color: #f2661c !important;
"></i>
                                    </a>
                                </div>
                                <div class="form-check" style="font-size: 13px">
                                    <a href="{{url('login/forgot-password')}}" class="float-end text-white">
                                        Forgot your password?
                                    </a>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" @if(isset($_COOKIE['email'])) checked
                                           @endif type="checkbox" name="remember" id="rememberMe">
                                    <label style="color: rgb(160, 174, 192)" class="form-check-label" for="rememberMe">Remember
                                        me</label>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn w-100 my-4 mb-2"
                                            style="background-color: #f2661c;color:white">Sign in
                                    </button>
                                </div>
                                <p class="text-sm text-center mt-3 mb-0" style="color: rgb(160, 174, 192)">Don't have an
                                    account? <a href="{{ url('register') }}" class="text-dark font-weight-bolder">Sign
                                        up</a>
                                </p>

                            </form>
                        </div>
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
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash'); // Switch to eye-slash icon
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye'); // Switch back to eye icon
                }
            });
        }
    });
</script>
