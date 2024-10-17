@extends('user_type.guest', ['parentFolder' => 'authentication', 'childFolder' => 'error', 'hasFooter' => 'footer', 'navbar' => 'cover'])

@section('content')
<!--navbar basic-->
  <main class="main-content  mt-0">
    <section class="my-10">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 my-auto">
            <h1 class="display-1 text-bolder text-gradient text-danger">Error 404</h1>
            <h2 class="text-white">{{request()->segment(1) !== 'login' || request()->segment(1) !== 'register' ? 'Practitioner' : 'Erm. Page'}} not found</h2>
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
