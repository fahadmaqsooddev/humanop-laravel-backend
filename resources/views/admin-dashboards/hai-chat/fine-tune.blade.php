@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')

    @push('css')
        <style>

            input::placeholder{
                color: white !important;
            }

            #textarea::placeholder{
                color: white !important;
            }

        </style>
    @endpush


    <div class="container-fluid my-3 py-3">
    @include('layouts.message')
    <!-- Main content -->

        <main class="d-flex justify-content-center">
            <div class="col-md-12 col-lg-11">

                <div id="content w-100">

                    @livewire('admin.hai-chat.fine-tune')


                </div>

            </div>
        </main>
        @include('layouts/footers/auth/footer')
    </div>
@endsection
