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

                    @livewire('admin.new-hai-chat.brains.create-brain', ["name" => $name, "description" => $description])


                </div>

            </div>
        </main>
        @include('layouts/footers/auth/footer')
    </div>

    <script>

        let isFormChanged = false;

        // Example: Set this to true when user modifies a form
        document.querySelectorAll(".change-input-form").forEach((el) => {

            el.addEventListener("keydown", () => {
                isFormChanged = true;
            });

        });

        // Prompt before leaving
        window.addEventListener("beforeunload", (event) => {
            if (isFormChanged) {
                event.preventDefault(); // Some browsers require this
                event.returnValue = ""; // Required for most browsers to trigger prompt
                // Chrome shows a generic prompt; custom messages are ignored for security reasons
            }
        });

        document.querySelectorAll(".update-button").forEach((el) => {

            el.addEventListener("click", () => {
                isFormChanged = false; // prevent the unload prompt
            });

        });

    </script>
@endsection
