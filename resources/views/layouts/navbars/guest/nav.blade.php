<!-- Navbar -->
<nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 my-3 w-100 shadow-none navbar-transparent mt-4">
  <div class="container" style="justify-content: center">
      <a class="navbar-brand d-flex flex-column font-weight-bolder ms-lg-0 ms-3 text-white"
         href="{{ Auth::check() && Auth::user()->is_admin == 2 ? url('client/dashboard') : url('login') }}">
          <img src="{{ Route::is('test_play') ? asset('assets/logos/HumanOp dark.png') : asset('assets/img/new_logo.png') }}" alt=""
          style="width: auto; height: 80px; margin-left: -5px;">
      </a>

      @if(Route::is('test_play'))
          <a href="{{url('login')}}" class="rainbow-border-user-nav-btn mt-4">
              Save and Exit
          </a>
      @else
          <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
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
