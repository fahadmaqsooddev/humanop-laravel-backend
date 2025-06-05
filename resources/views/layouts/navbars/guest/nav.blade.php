<!-- Navbar -->
<nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 my-3 w-100 shadow-none navbar-transparent mt-4">
    <div class="container" style="justify-content: center">
        @if(Auth::check() && Auth::user()->is_admin == 2 && Auth::user()->practitioner_id == null)
            <a class="navbar-brand d-flex flex-column font-weight-bolder ms-lg-0 ms-3 text-white"
               href="{{ url('client/dashboard') }}">
                <img src="{{ asset('assets/img/new_logo_dark.png') }}"
                     alt="" style="width: auto; height: 80px; margin-left: -5px;">
            </a>
        @elseif(Auth::check() && Auth::user()->is_admin == 2 && Auth::user()->practitioner_id != null)
            <a class="navbar-brand d-flex flex-column font-weight-bolder ms-lg-0 ms-3 text-white"
               href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('dashboard') }}">
                <img src="{{ asset('assets/img/new_logo_dark.png') }}"
                     alt="" style="width: auto; height: 80px; margin-left: -5px;">
            </a>
        @elseif(Auth::check() && (Auth::user()->is_admin == 4))
            <a class="navbar-brand d-flex flex-column font-weight-bolder ms-lg-0 ms-3 text-white"
               href="{{ url('practitioner/dashboard') }}">
                <img src="{{ asset('assets/img/new_logo_dark.png') }}"
                     alt="" style="width: auto; height: 80px; margin-left: -5px;">
            </a>
        @else
            <a class="navbar-brand d-flex flex-column font-weight-bolder ms-lg-0 ms-3 text-white"
               href="{{ request()->segment(1) != null && request()->segment(1) !== 'login' ? \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('login') :  url('login') }}">
                <img src="{{ asset('assets/img/new_logo_dark.png') }}"
                     alt="" style="width: auto; height: 80px; margin-left: -5px;">
            </a>
        @endif

        @if(Auth::check() && (Route::is('test_play') && (Auth::user()->is_admin == 2) && Auth::user()->practitioner_id == null))
            <a href="{{url('login')}}" class="connection-btn mt-4" style="font-size: 16px !important;padding: 8px 16px !important;">
                Save and Exit
            </a>
        @elseif(Auth::check() && Auth::user()->is_admin == 2 && Auth::user()->practitioner_id != null)
            <a href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('dashboard') }}" class="connection-btn mt-4" style="font-size: 16px !important;padding: 8px 16px !important;">
                Save and Exit
            </a>
        @elseif(Auth::check() && (Route::is('admin_test_play') && (Auth::user()->is_admin == 4)))
            <a href="{{ url('practitioner/dashboard') }}" class="connection-btn mt-4" style="font-size: 16px !important;padding: 8px 16px !important;">
                Save and Exit
            </a>
        @else
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                    aria-label="Toggle navigation">
                {{-- <span class="navbar-toggler-icon mt-2"> --}}
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
                {{--      </span>--}}
            </button>
        @endif

    </div>
</nav>
<!-- End Navbar -->
