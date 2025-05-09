@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'virtual'])

@section('content')

    <div class="container-fluid my-3 py-3">
    @include('layouts.message')
    <!-- Main content -->

        <main class="d-flex justify-content-center">
            <div class="col-md-12 col-lg-11">

                <div id="content w-100">

                    @livewire('admin.hai-chat.dojo.dojo')

                </div>

            </div>
        </main>
        @include('layouts/footers/auth/footer')
    </div>

@endsection
