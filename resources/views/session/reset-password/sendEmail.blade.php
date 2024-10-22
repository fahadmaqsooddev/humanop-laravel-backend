@extends('user_type.guest', ['parentFolder' => 'session', 'childFolder' => 'none'])

@section('content')

    <main class="main-content mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
             style="background-image: url('assets/img/login.webp');">
{{--             <span class="mask bg-gradient-dark opacity-6"></span>--}}
            <div class="container">
                <div class="row d-flex flex-column justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
{{--                        <h1 class="text-white mb-2 mt-5">Welcome!</h1>--}}
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
                                <h3 style="color: #0f1535;">Reset Password</h3>
                                <p class="mb-0" style="color: #0f1535;">You will receive an e-mail in maximum 60 seconds</p>
                            </div>
                        <div class="card-body">
                            <form action="/forgot-password" method="POST" role="form text-left">
                                @csrf
                                <div>
                                    <label style="color: #0f1535; font-size: 15px">Email</label>
                                    <div class=" mb-3">
                                        <input type="email" class="form-control" placeholder="Enter your e-mail" aria-label="Email" style="background-color: #f3deba; color: black; border-radius: 15px;" aria-describedby="email-addon" name="email" id="email" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn  w-100 mt-4 mb-0 text-white" style="background-color: #f2661c !important;">Reset</button>
                                    <div class="mt-3" style="color: #0f1535;"> Already have an account?  <a href="{{url('login')}}" style="color: #0f1535; font-weight: bold">Sign in</a></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
