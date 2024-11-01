@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

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


                    <!-- Train section -->
                    <div id="train" class="content-page" style="display: flex;">
                        <!-- Responsive Dropdown Section -->


                        <div class="d-flex flex-column flex-md-row gap-3 w-100">
                            <!-- Left Column -->
                            <div class="col-md-4">
                                <!-- Search Box -->
                                <div class="container-fluid mt-4 mx-0 px-0">
                                    <div class="textarea-with-icon">
                                        <textarea class="form-control" rows="3" style="font-size: small; background-color: #f3deba; color: #0f1534"
                                                  placeholder="Search across all documents"></textarea>
                                    </div>
                                </div>

                                <!-- Responsive Tabs -->
                                <ul class="nav nav-tabs justify-content-between flex-wrap mt-3">
                                    <li class="nav-item">
                                        <a class="nav-link main-heading active" aria-current="page" href="#">All</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link main-heading" href="#">Pending</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link main-heading" href="#">Deleted</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link main-heading" href="#">Failed</a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Right Column with Upload Options -->
                            <div class="col-md-8">
                                <div class="d-flex flex-wrap justify-content-between p-5">
                                    <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                                        <i class="bi bi-graph-up"></i>
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <div class="fw-bold main-heading">Upload files</div>
                                            <div class="fs-7 main-heading">Files supported: TXT, PDF</div>
                                        </div>
                                        <input type="file" id="fileInput" style="display: none;" />
                                        <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                                class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                            upload
                                        </button>
                                    </div>

                                    <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                                        <i class="bi bi-graph-up"></i>
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <div class="fw-bold main-heading">From Text</div>
                                            <div class="fs-7 main-heading">Files supported: TXT, PDF</div>
                                        </div>
                                        <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                                class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                            add
                                        </button>
                                    </div>

                                    <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                                        <i class="bi bi-graph-up"></i>
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <div class="fw-bold main-heading">From questions and answers</div>
                                            <div class="fs-7 main-heading">Files supported: TXT, PDF</div>
                                        </div>
                                        <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                                class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                            add
                                        </button>
                                    </div>
                                </div>
                                <hr />
                                <div>

                                    <div class="flex-column">
                                        <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                                            <i class="bi bi-graph-up"></i>
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <div class="fw-bold main-heading">From a webpage</div>
                                                <div class="fs-7 main-heading">One link in each line (max 200)</div>
                                            </div>
                                            <input type="file" id="fileInput" style="display: none;" />
                                        </div>
                                        <input type="text" placeholder="https://..." class="form-control my-2" style="background-color: #f3deba" id="exampleInputPassword1">
                                        <div class="d-flex justify-content-between">
                                            <div class="form-check form-switch">
                                                <label class="form-check-label" for="flexSwitchCheckChecked">Extract URLs</label>

                                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                                            </div>
                                            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                                    class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                                scrape
                                            </button>

                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div>

                                    <div class="flex-column">
                                        <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                                            <i class="bi bi-graph-up"></i>
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <div class="fw-bold main-heading">From a Youtube Video</div>
                                                <div class="fs-7 main-heading">Youtube video must have captions</div>
                                            </div>
                                            <input type="file" id="fileInput" style="display: none;" />
                                        </div>
                                        <input type="text" placeholder="https://www.youtube.com?watch..." style="background-color: #f3deba" class="form-control my-2" id="exampleInputPassword1">
                                        <div class="d-flex justify-content-between">
                                            <div class="form-check form-switch">
                                                <label class="form-check-label" for="flexSwitchCheckChecked">Extract URLs</label>

                                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                                            </div>
                                            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                                    class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                                scrape
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
