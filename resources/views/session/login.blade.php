@extends('user_type.guest', ['parentFolder' => 'session', 'childFolder' => 'none'])

@section('content')
<main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
        style="background-image: url('assets/img/login.webp');">
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
                <div class="card z-index-0">
                    @if($errors->any())
                    <div class="m-3  alert alert-warning alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">
                            {{$errors->first()}}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                        <span class="alert-text text-white">
                            {{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    <div class="card-header text-center pt-4">
                        <h5 class="text-white">Register With</h5>
                    </div>
                    <div class="css-mgpqwz">
                        <div class="d-flex justify-content-center" style="margin-right: 20px;">
                            <a href="#">
                                <button class="btn btn-primary p-2 border border-radius-lg border-gray-800" type="button" style="background-color: rgb(19, 21, 54);">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" 
                                         class="material-icons-round notranslate" aria-hidden="true" height="40px" width="40px" 
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z">
                                        </path>
                                    </svg>
                                </button>
                            </a>
                        </div>
                        <div class="d-flex justify-content-center " style="margin-right: 20px;>
                            <a href="#">
                                <button class="btn btn-primary p-2 border border-radius-lg border-gray-800" type="button" style="background-color: rgb(19, 21, 54);">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 384 512" aria-hidden="true"
                                         height="40px" width="40px" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z">
                                        </path>
                                    </svg>
                                </button>
                            </a>
                        </div>
                        <div class="d-flex justify-content-center" style="margin-right: 20px;>
                            <a href="#">
                                <button class="btn btn-primary  p-2 border border-radius-lg border-gray-800" type="button" style="background-color: rgb(19, 21, 54);">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 488 512" aria-hidden="true"
                                         height="40px" width="40px" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z">
                                        </path>
                                    </svg>
                                </button>
                            </a>
                        </div>               
                    </div>
                    <p class="text-center text-white"><b>or</b></p>
                    <div class="card-body">
                        <form role="form" class="text-start" action="{{url('/session')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Email" aria-label="Email"
                                    value="admin@softui.com" name="email" id="email" value="{{ old('email') }}"
                                    required style="background-color: #0F1535; color: white; border-radius: 15px;">
                                @error('email')
                                <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="Password" aria-label="Password"
                                    value="secret" name="password" id="password" required style="background-color: #0F1535; color: white; border-radius: 15px;">
                                @error('password')
                                <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label style="color: rgb(160, 174, 192)" class="form-check-label" for="rememberMe">Remember me</label>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn w-100 my-4 mb-2" style="background-color: #f2661c;color:white">Sign in</button>
                            </div>
                            <p class="text-sm text-center mt-3 mb-0" style="color: rgb(160, 174, 192)">Don't have an account? <a href="{{ url('register') }}" class="text-dark font-weight-bolder">Sign up</a></p>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
