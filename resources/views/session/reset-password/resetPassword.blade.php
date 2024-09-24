@extends('user_type.guest', ['parentFolder' => 'session', 'childFolder' => 'none'])

@section('content')
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
             style="background-image: url('assets/img/login.webp');">
            {{-- <span class="mask bg-gradient-dark opacity-6"></span> --}}
            {{--            <div class="container">--}}
            {{--                <div class="row d-flex flex-column justify-content-center">--}}
            {{--                    <div class="col-lg-5 text-center mx-auto">--}}
            {{--                        <h1 class="text-white mb-2 mt-5">Welcome!</h1>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
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
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
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
                        <div class="card-header pb-0 text-left">
                            <h4 class="mb-0" style="color: #f2661c !important;">Change password</h4>
                        </div>
                        <div class="card-body">
                            <form role="form" action="/reset-password" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div>
                                    <label for="email">Email</label>
                                    <div class="">
                                        <input id="email" name="email" type="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon" value="{{ old('email') }}" required>
{{--                                        @error('email')--}}
{{--                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>--}}
{{--                                        @enderror--}}
                                    </div>
                                </div>
                                <div>
                                    <label for="password">New Password</label>
                                    <div class="">
                                        <input id="password" name="password" type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon" required>
{{--                                        @error('password')--}}
{{--                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>--}}
{{--                                        @enderror--}}
                                    </div>
                                </div>
                                <div>
                                    <label for="password_confirmation">Confirm Password</label>
                                    <div class="">
                                        <input id="password-confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Password-confirmation" aria-label="Password-confirmation" aria-describedby="Password-addon" required>
{{--                                        @error('password')--}}
{{--                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>--}}
{{--                                        @enderror--}}
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn w-100 mt-4 mb-0 text-white" style="background-color: #f2661c !important;">Recover your password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    </main>

@endsection
