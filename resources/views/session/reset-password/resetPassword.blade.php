@extends('user_type.guest', ['parentFolder' => 'session', 'childFolder' => 'none'])

@section('content')
    <main class="main-content mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
             style="background-image: url('assets/img/login.webp');">
            {{--             <span class="mask bg-gradient-dark opacity-6"></span>--}}
            <div class="container">
                <div class="row d-flex flex-column justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                        {{--                                    <h1 class="text-white mb-2 mt-5">Welcome!</h1>--}}
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
                                <input type="hidden" name="email" value="{{$email}}">
                                {{--                                <div>--}}
                                {{--                                    <label for="email">Email</label>--}}
                                {{--                                    <div class="">--}}
                                {{--                                        <input id="email" name="email" type="email" class="form-control" style="background-color: #0F1535; color: white; border-radius: 15px;" placeholder="Email" aria-label="Email" aria-describedby="email-addon" value="{{ old('email') }}" required>--}}
                                {{--                                        @error('email')--}}
                                {{--                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>--}}
                                {{--                                        @enderror--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="mt-3 position-relative">
                                    <div>
                                        <label for="password" style="color: #0f1535; font-size: 15px">Password</label>
                                        <input type="password" class="form-control" placeholder="Password"
                                               aria-label="Password" aria-describedby="password-addon"
                                               name="password"
                                               id="password"
                                               style="background-color: #f3deba; color: black; border-radius: 15px;">
                                        <!-- Eye icon for toggling password visibility -->
                                        <span class="position-absolute" id="togglePassword"
                                              style="right: 15px; top: 50px; transform: translateY(-50%); cursor: pointer; color: white; z-index: 10;">
                                        <i class="fas fa-eye pt-1 password-eye" id="password-eye"
                                           style="    color: #f2661c !important;"></i>
                                    </span>
                                        <div id="validatePassword"></div>
                                        @error('password')
                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-3 position-relative">
                                    <div>
                                        <label for="password" style="color: #0f1535; font-size: 15px">Confirm
                                            Password</label>
                                        <input type="password" class="form-control"
                                               placeholder="Confirm Password"
                                               aria-label="Password" aria-describedby="password-addon"
                                               name="password_confirmation"
                                               id="confirmPassword"
                                               style="background-color: #f3deba; color: black; border-radius: 15px;">
                                        <div id="validateConfirmPassword"></div>
                                        @error('password_confirmation')
                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                    @enderror
                                    <!-- Eye icon for toggling password visibility -->
                                        <span class="position-absolute" id="toggleConfirmPassword"
                                              style="right: 15px; top: 50px; transform: translateY(-50%); cursor: pointer; color: white; z-index: 10;">
                                        <i class="fas fa-eye pt-1 confirm-password-eye" id="confirm-password-eye"
                                           style="    color: #f2661c !important;"></i>
                                    </span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn w-100 mt-4 mb-0 text-white"
                                            style="background-color: #f2661c !important;">Recover your password
                                    </button>
                                    <div class="mt-3" style="color: #0f1535"> Already have an account? <a style="color: #0f1535" href="{{url('login')}}"
                                                                                   class="font-weight-bolder">Sign in</a></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    </main>

@endsection
