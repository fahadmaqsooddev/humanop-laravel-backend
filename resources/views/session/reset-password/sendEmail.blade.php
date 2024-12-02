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

        @livewire('admin.user.reset-password')

    </main>
@endsection
