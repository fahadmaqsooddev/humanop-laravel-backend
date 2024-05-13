@extends('user_type.guest', ['parentFolder' => 'session', 'childFolder' => 'none'])

@section('content')
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
             style="background-image: url('assets/img/curved-images/curved9.jpg');">
        <span class="mask bg-gradient-dark opacity-6"></span>
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
                <h5>Sign in</h5>
                </div>
                <div class="card-body">
                <form role="form" class="text-start" action="{{url('/session')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                    <input type="email" class="form-control" placeholder="Email" aria-label="Email" value="admin@softui.com" name="email" id="email" value="{{ old('email') }}" required>
                    @error('email')
                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="mb-3">
                    <input type="password" class="form-control" placeholder="Password" aria-label="Password" value="secret" name="password" id="password" required>
                    @error('password')
                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <div class="text-center">
                    <button type="submit" class="btn bg-gradient-info w-100 my-4 mb-2">Sign in</button>
                    </div>
                    <div class="mb-2 position-relative text-center">
                    <p class="text-sm font-weight-bold mb-2 text-secondary text-border d-inline z-index-2 bg-white px-3">
                        or
                    </p>
                    </div>
                    <div class="text-center">
                    <a href="/register" class="btn bg-gradient-dark w-100 mt-2 mb-4">Sign up</a>
                    </div>
                    <p class="text-sm mt-3 mb-0">Forgot your password? Reset your password
                        <a href="/login/forgot-password" class="text-dark font-weight-bolder">here</a>
                    </p>
                </form>
                </div>
            </div>
            </div>
        </div>
        </div>
    </main>
@endsection
