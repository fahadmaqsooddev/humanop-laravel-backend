@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
@php
    $urlParts = explode('/', request()->url());
    $lastSegment = array_pop($urlParts);
@endphp
@section('content')
    @push('css')
        <style>
            .setting-options:hover {
                background-color: white !important;
            }

            .text-color-dark {
                color: #0f1534 !important;
            }

            input::placeholder {
                color: #0f1534 !important;
            }

            .setting-form-heading {
                font-size: 22px;
                font-weight: bold;
            }

            .card
            {
                background-color: #8bb1ab !important;
                box-shadow: none !important;
            }

            .main-heading
            {
                color: #0f1534 !important;
            }

            ::placeholder
            {
                color: #0f1534;
            }

        </style>
    @endpush
    <div class="container-fluid my-3 py-3">
        @include('layouts.message')
        <!-- Main content -->
            <main class="col-md-12 col-lg-10 justify-content-center ">
                <div id="content">

                    @livewire('admin.hai-chat.search-embedding',['name' => $lastSegment])

                    <!-- Train section -->


                </div>
            </main>
        @include('layouts/footers/auth/footer')
    </div>
@endsection

@push('js')
    <script src="{{ URL::asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        if (document.getElementById('choices-gender')) {
            var gender = document.getElementById('choices-gender');
            const example = new Choices(gender);
        }
        if (document.getElementById('choices-age')) {
            var age = document.getElementById('choices-age');
            const example = new Choices(age);
        }
        if (document.getElementById('choices-language')) {
            var language = document.getElementById('choices-language');
            const example = new Choices(language);
        }

        if (document.getElementById('choices-skills')) {
            var skills = document.getElementById('choices-skills');
            const example = new Choices(skills, {
                delimiter: ',',
                editItems: true,
                maxItemCount: 5,
                removeItemButton: true,
                addItems: true
            });
        }

        if (document.getElementById('choices-year')) {
            var year = document.getElementById('choices-year');
            setTimeout(function () {
                const example = new Choices(year);
            }, 1);

            for (y = 1900; y <= 2020; y++) {
                var optn = document.createElement("OPTION");
                optn.text = y;
                optn.value = y;

                if (y == 2020) {
                    optn.selected = true;
                }

                year.options.add(optn);
            }
        }

        if (document.getElementById('choices-day')) {
            var day = document.getElementById('choices-day');
            setTimeout(function () {
                const example = new Choices(day);
            }, 1);


            for (y = 1; y <= 31; y++) {
                var optn = document.createElement("OPTION");
                optn.text = y;
                optn.value = y;

                if (y == 1) {
                    optn.selected = true;
                }

                day.options.add(optn);
            }

        }

        if (document.getElementById('choices-month')) {
            var month = document.getElementById('choices-month');
            setTimeout(function () {
                const example = new Choices(month);
            }, 1);

            var d = new Date();
            var monthArray = new Array();
            monthArray[0] = "January";
            monthArray[1] = "February";
            monthArray[2] = "March";
            monthArray[3] = "April";
            monthArray[4] = "May";
            monthArray[5] = "June";
            monthArray[6] = "July";
            monthArray[7] = "August";
            monthArray[8] = "September";
            monthArray[9] = "October";
            monthArray[10] = "November";
            monthArray[11] = "December";
            for (m = 0; m <= 11; m++) {
                var optn = document.createElement("OPTION");
                optn.text = monthArray[m];
                // server side month start from one
                optn.value = (m + 1);
                // if june selected
                if (m == 1) {
                    optn.selected = true;
                }
                month.options.add(optn);
            }
        }

        function visible() {
            var elem = document.getElementById('profileVisibility');
            if (elem) {
                if (elem.innerHTML == "Switch to visible") {
                    elem.innerHTML = "Switch to invisible"
                } else {
                    elem.innerHTML = "Switch to visible"
                }
            }
        }

        var openFile = function (event) {
            var input = event.target;

            // Instantiate FileReader
            var reader = new FileReader();
            reader.onload = function () {
                imageFile = reader.result;

                document.getElementById("imageChange").innerHTML = '<img width="200" src="' + imageFile + '" class="rounded-circle w-100 shadow" />';
            };
            reader.readAsDataURL(input.files[0]);
        };
    </script>
@endpush
