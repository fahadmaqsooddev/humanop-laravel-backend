@extends('user_type.guest', ['parentFolder' => 'session', 'childFolder' => 'none'])
<style>
    .left-nav-blue-light-color {
        background: #2C4C7E !important;
    }
</style>
@section('content')
    <main class="main-content mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
        >
            {{-- <span class="mask bg-gradient-dark opacity-6"></span> --}}
            <div class="container">
                <div class="row d-flex flex-column justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                        <h1 class="text-white mb-2 mt-5">Welcome!</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                    <div class="card z-index-0" style="background-color: #F3DEBA">

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
                            <h5 class="" style="color: #0f1535">Click To Login With:</h5>
                        </div>
                        <div class="css-mgpqwz d-flex justify-content-center">
                            <div>
                                @if(!empty($slug) && !empty($slug2))
                                <a href="{{ url('/' . $slug. '/'. $slug2 .'/auth/google') }}">
                                    <button class="btn btn-primary bg-light p-2 border border-radius-lg border-gray-800"
                                            type="button"
                                            style="background-color: rgb(19, 21, 54);">
                                        <img src="https://www.svgrepo.com/show/303108/google-icon-logo.svg" alt=""
                                             width="30px" ;>
                                    </button>
                                </a>
                                @else
                                    <a href="{{ url('auth/google') }}">
                                        <button class="btn btn-primary bg-light p-2 border border-radius-lg border-gray-800"
                                                type="button"
                                                style="background-color: rgb(19, 21, 54);">
                                            <img src="https://www.svgrepo.com/show/303108/google-icon-logo.svg" alt=""
                                                 width="30px" ;>
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <p class="text-center" style="color: #0f1535"><b>Login with your email:</b></p>
                        <div class="card-body">
                            @if(request()->segment(1) === null || request()->segment(1) === 'login')
                                <form role="form" class="text-start" action="{{url('/session')}}" method="POST">
                                    @else
                                        <form role="form" class="text-start"
                                              action="{{\App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('login-client-to-practitioner')}}"
                                              method="POST">
                                            <input type="text" class="form-control" hidden name="slug" value="{{$slug}}"
                                                   style="background-color: #f3deba; color: black; border-radius: 15px;">
                                            <input type="text" class="form-control" hidden name="slug2"
                                                   value="{{$slug2}}"
                                                   style="background-color: #f3deba; color: black; border-radius: 15px;">
                                            @endif
                                            @csrf
                                            <div class="mb-3">
                                                <label for="email" style="color: #0f1535; font-size: 15px">Email</label>
                                                <input type="email" class="form-control" placeholder="Email"
                                                       aria-label="Email" name="email" id="email"
                                                       @if(isset($_COOKIE['email'])) value="{{ $_COOKIE['email'] }}"
                                                       @endif
                                                       style="background-color: #f3deba; color: black; border-radius: 15px;">
                                            </div>

                                            <div class="mb-1 position-relative">
                                                <label for="password"
                                                       style="color: #0f1535; font-size: 15px">Password</label>
                                                <input type="password" class="form-control" placeholder="Password"
                                                       aria-label="Password" name="password" id="password" maxlength="30"
                                                       @if(isset($_COOKIE['password'])) value="{{ $_COOKIE['password'] }}"
                                                       @endif
                                                       style="background-color: #f3deba; color: black; border-radius: 15px;">

                                                <!-- Password toggle icon -->
                                                <a class="position-absolute" id="togglePassword"
                                                   style="right: 15px; top: 65%; transform: translateY(-50%); cursor: pointer; color: white; z-index: 20;">
                                                    <i class="fas fa-eye-slash pt-1 password-eye" id="password-eye"
                                                       style="color: #f2661c !important;"></i>
                                                </a>
                                            </div>

                                            <div class="form-check" style="font-size: 13px">
                                                <a href="{{url('login/forgot-password')}}" class="float-end"
                                                   style="color: #0f1535; font-size: 15px">
                                                    Forgot your password?
                                                </a>
                                            </div>

                                            <div class="form-check form-switch">
                                                <input class="form-check-input" @if(isset($_COOKIE['email'])) checked
                                                       @endif type="checkbox" name="remember" id="rememberMe">
                                                <label class="form-check-label text-bold" for="rememberMe"
                                                       style="color: #0f1535">Remember me</label>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn w-100 my-4 mb-2"
                                                        style="background-color: #f2661c; color: white;">Sign in
                                                </button>
                                            </div>

                                            <p class="text-sm text-center mt-3 mb-0" style="color: #0f1535;">
                                                Don't have an account?
                                                @if(request()->segment(1) === 'login')
                                                    <a href="{{ url('register') }}" class="font-weight-bolder"
                                                       style="color: #0f1535;">Sign up</a>
                                                @else
                                                    <a href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('register') }}"
                                                       class="font-weight-bolder" style="color: #0f1535;">Sign up</a>
                                                @endif
                                            </p>
                                            <p class="text-sm text-center mt-3 mb-0" > <a style="color:rgb(210, 103, 34);" href="{{route('privacy')}}" >privacy policy</a>
                                            </p>
                                            <p class="text-sm text-center mt-3 mb-0" > <a style="color:rgb(210, 103, 34);" href="{{route('term_of_service')}}" >term of service</a>
                                            </p>
                                        </form>

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
