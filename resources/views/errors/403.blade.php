@extends('user_type.auth', ['parentFolder' => 'authentication', 'childFolder' => 'error', 'hasFooter' => 'footer', 'navbar' => 'cover'])

@section('content')

<!--navbar basic-->
  <main class="main-content  mt-0">
    <section class="my-10">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 my-auto">
            <h1 class="text-bolder text-gradient text-danger">Unauthorized Access</h1>
            <p class="lead text-white">You'r Not Authorized to Access This Page</p>
            <a href="{{route('admin_dashboard')}}" type="button" class="btn text-white mt-4" style="background-color: #1b3a62">Go to Homepage</a>
          </div>
          <div class="col-lg-6 my-auto position-relative">
            <img class="w-100 position-relative" src="{{ URL::asset('assets/img/illustrations/error-404.png') }}" alt="404-error">
          </div>
        </div>
      </div>
    </section>
  </main>
<!--footer socials-->
@endsection
