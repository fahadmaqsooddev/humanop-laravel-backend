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
                color: black !important;
            }

            .setting-form-heading {
                font-size: 22px;
                font-weight: bold;
            }

            .card-bg-white-orange-border{
                background-color: #FFFFFF !important;
                border: 2px solid #d26622 !important;
            }

            .text-orange{
                color: #f2661c !important;
            }

            .input-bg{
                background-image: linear-gradient(#F3DEBA, #F3DEBA), linear-gradient(90deg, rgb(146, 11, 11), orange, yellow, rgb(22, 200, 22), rgb(0, 238, 255), rgb(26, 58, 222), rgb(4, 19, 113));
                color: black !important;
            }

            .input-bg::placeholder{
                color: black !important;
            }

        </style>

    @endpush
    <div class="container-fluid my-1 py-1">
        @include('layouts.message')
        <div class="row">
            <div class="col-lg-3">
                <div class="position-sticky top-1">

                    <div class="card">

                        <ul class="nav rainbow-border-user-nav-btn flex-column border-radius-lg p-3">
                            <li class="nav-item">
                                <a class="nav-link setting-options text-body" data-scroll="" href="#persona">
                                    <div class="icon me-2">
                                        <svg class="text-dark mb-1" width="16px" height="16px" viewBox="0 0 40 40"
                                             version="1.1" xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>spaceship</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-1720.000000, -592.000000)" fill="#193862"
                                                   fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(4.000000, 301.000000)">
                                                            <path class="color-background"
                                                                  d="M39.3,0.706666667 C38.9660984,0.370464027 38.5048767,0.192278529 38.0316667,0.216666667 C14.6516667,1.43666667 6.015,22.2633333 5.93166667,22.4733333 C5.68236407,23.0926189 5.82664679,23.8009159 6.29833333,24.2733333 L15.7266667,33.7016667 C16.2013871,34.1756798 16.9140329,34.3188658 17.535,34.065 C17.7433333,33.98 38.4583333,25.2466667 39.7816667,1.97666667 C39.8087196,1.50414529 39.6335979,1.04240574 39.3,0.706666667 Z M25.69,19.0233333 C24.7367525,19.9768687 23.3029475,20.2622391 22.0572426,19.7463614 C20.8115377,19.2304837 19.9992882,18.0149658 19.9992882,16.6666667 C19.9992882,15.3183676 20.8115377,14.1028496 22.0572426,13.5869719 C23.3029475,13.0710943 24.7367525,13.3564646 25.69,14.31 C26.9912731,15.6116662 26.9912731,17.7216672 25.69,19.0233333 L25.69,19.0233333 Z"></path>
                                                            <path class="color-background"
                                                                  d="M1.855,31.4066667 C3.05106558,30.2024182 4.79973884,29.7296005 6.43969145,30.1670277 C8.07964407,30.6044549 9.36054508,31.8853559 9.7979723,33.5253085 C10.2353995,35.1652612 9.76258177,36.9139344 8.55833333,38.11 C6.70666667,39.9616667 0,40 0,40 C0,40 0,33.2566667 1.855,31.4066667 Z"></path>
                                                            <path class="color-background"
                                                                  d="M17.2616667,3.90166667 C12.4943643,3.07192755 7.62174065,4.61673894 4.20333333,8.04166667 C3.31200265,8.94126033 2.53706177,9.94913142 1.89666667,11.0416667 C1.5109569,11.6966059 1.61721591,12.5295394 2.155,13.0666667 L5.47,16.3833333 C8.55036617,11.4946947 12.5559074,7.25476565 17.2616667,3.90166667 L17.2616667,3.90166667 Z"
                                                                  opacity="0.598539807"></path>
                                                            <path class="color-background"
                                                                  d="M36.0983333,22.7383333 C36.9280725,27.5056357 35.3832611,32.3782594 31.9583333,35.7966667 C31.0587397,36.6879974 30.0508686,37.4629382 28.9583333,38.1033333 C28.3033941,38.4890431 27.4704606,38.3827841 26.9333333,37.845 L23.6166667,34.53 C28.5053053,31.4496338 32.7452344,27.4440926 36.0983333,22.7383333 L36.0983333,22.7383333 Z"
                                                                  opacity="0.598539807"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="text-sm custom-text-dark">Persona</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link setting-options text-body" data-scroll="" href="#prompt">
                                    <div class="icon me-2">
                                        <svg class="text-dark mb-1" width="16px" height="16px" viewBox="0 0 40 40"
                                             version="1.1" xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>spaceship</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-1720.000000, -592.000000)" fill="#193862"
                                                   fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(4.000000, 301.000000)">
                                                            <path class="color-background"
                                                                  d="M39.3,0.706666667 C38.9660984,0.370464027 38.5048767,0.192278529 38.0316667,0.216666667 C14.6516667,1.43666667 6.015,22.2633333 5.93166667,22.4733333 C5.68236407,23.0926189 5.82664679,23.8009159 6.29833333,24.2733333 L15.7266667,33.7016667 C16.2013871,34.1756798 16.9140329,34.3188658 17.535,34.065 C17.7433333,33.98 38.4583333,25.2466667 39.7816667,1.97666667 C39.8087196,1.50414529 39.6335979,1.04240574 39.3,0.706666667 Z M25.69,19.0233333 C24.7367525,19.9768687 23.3029475,20.2622391 22.0572426,19.7463614 C20.8115377,19.2304837 19.9992882,18.0149658 19.9992882,16.6666667 C19.9992882,15.3183676 20.8115377,14.1028496 22.0572426,13.5869719 C23.3029475,13.0710943 24.7367525,13.3564646 25.69,14.31 C26.9912731,15.6116662 26.9912731,17.7216672 25.69,19.0233333 L25.69,19.0233333 Z"></path>
                                                            <path class="color-background"
                                                                  d="M1.855,31.4066667 C3.05106558,30.2024182 4.79973884,29.7296005 6.43969145,30.1670277 C8.07964407,30.6044549 9.36054508,31.8853559 9.7979723,33.5253085 C10.2353995,35.1652612 9.76258177,36.9139344 8.55833333,38.11 C6.70666667,39.9616667 0,40 0,40 C0,40 0,33.2566667 1.855,31.4066667 Z"></path>
                                                            <path class="color-background"
                                                                  d="M17.2616667,3.90166667 C12.4943643,3.07192755 7.62174065,4.61673894 4.20333333,8.04166667 C3.31200265,8.94126033 2.53706177,9.94913142 1.89666667,11.0416667 C1.5109569,11.6966059 1.61721591,12.5295394 2.155,13.0666667 L5.47,16.3833333 C8.55036617,11.4946947 12.5559074,7.25476565 17.2616667,3.90166667 L17.2616667,3.90166667 Z"
                                                                  opacity="0.598539807"></path>
                                                            <path class="color-background"
                                                                  d="M36.0983333,22.7383333 C36.9280725,27.5056357 35.3832611,32.3782594 31.9583333,35.7966667 C31.0587397,36.6879974 30.0508686,37.4629382 28.9583333,38.1033333 C28.3033941,38.4890431 27.4704606,38.3827841 26.9333333,37.845 L23.6166667,34.53 C28.5053053,31.4496338 32.7452344,27.4440926 36.0983333,22.7383333 L36.0983333,22.7383333 Z"
                                                                  opacity="0.598539807"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="text-sm custom-text-dark">Prompt</span>
                                </a>
                            </li>
                            <li class="nav-item pt-2">
                                <a class="nav-link setting-options text-body" data-scroll="" href="#keyword_restriction">
                                    <div class="icon me-2">
                                        <svg class="text-dark mb-1" width="16px" height="16px" viewBox="0 0 40 44"
                                             version="1.1" xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>document</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-1870.000000, -591.000000)" fill="#193862"
                                                   fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(154.000000, 300.000000)">
                                                            <path class="color-background"
                                                                  d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z"
                                                                  opacity="0.603585379"></path>
                                                            <path class="color-background"
                                                                  d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="text-sm custom-text-dark">Keyword Restrictions</span>
                                </a>
                            </li>
                            <li class="nav-item pt-2">
                                <a class="nav-link text-body setting-options" data-scroll="" href="#conversation">
                                    <div class="icon me-2">
                                        <svg class="text-dark mb-1" width="16px" height="16px" viewBox="0 0 42 42"
                                             version="1.1" xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>box-3d-50</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2319.000000, -291.000000)" fill="#193862"
                                                   fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(603.000000, 0.000000)">
                                                            <path class="color-background"
                                                                  d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z"></path>
                                                            <path class="color-background"
                                                                  d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z"
                                                                  opacity="0.7"></path>
                                                            <path class="color-background"
                                                                  d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z"
                                                                  opacity="0.7"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="text-sm custom-text-dark">Conversation</span>
                                </a>
                            </li>

                        </ul>

                    </div>

                    @livewire('admin.hai-chat.persona-listing')
                </div>

            </div>
{{--            @php--}}
{{--                $urlParts = explode('/', request()->url());--}}
{{--                $lastSegment = array_pop($urlParts);--}}
{{--            @endphp--}}
            <div class="col-lg-9 mt-lg-0 mt-4">

                @if(isset($brain))

                    @livewire('admin.hai-chat.persona',['chat_bot_id' => $brain['id']])

                    @livewire('admin.hai-chat.setting.prompt',['chat_bot_id' => $brain['id'], 'name' => $brain['name']])

                    @livewire('admin.hai-chat.setting.conversation',['chat_bot_id' => $brain['id'], 'name' => $brain['name']])

                @else

                    @livewire('admin.hai-chat.persona',['chat_bot_id' => null])

                    @livewire('admin.hai-chat.setting.prompt',['chat_bot_id' => null, 'name' => null])

                    @livewire('admin.hai-chat.setting.conversation',['name' => null, 'chat_bot_id' => null])

                @endif



{{--                @livewire('admin.hai-chat.persona')--}}
{{--                @livewire('admin.hai-chat.setting.prompt')--}}

{{--                @livewire('admin.hai-chat.setting.embedding',['bot_name' => $lastSegment])--}}

{{--                <div class="card setting-box-background mt-4" id="capture">--}}
{{--                    <div class="card-header">--}}
{{--                        <h5 class="text-color-dark setting-form-heading">Capture</h5>--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-12 col-sm-12">--}}
{{--                                <label class="form-label mt-4 text-color-dark">Email</label>--}}
{{--                                <div class="input-group">--}}
{{--                                    <input id="email"--}}

{{--                                           class="form-control text-color-dark setting-box-background" type="email"--}}
{{--                                           placeholder="Enter your email">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-12 col-sm-12">--}}
{{--                                <label class="form-label mt-4 text-color-dark">Type</label><br>--}}
{{--                                <span class="mt-4 text-color-dark">What type of data is this?</span>--}}
{{--                                <div class="input-group">--}}
{{--                                    <input id="email"--}}

{{--                                           class="form-control text-color-dark setting-box-background" type="email"--}}
{{--                                           placeholder="Enter your email">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-12 col-sm-12">--}}
{{--                                <label class="form-label mt-4 text-color-dark">Description</label><br>--}}
{{--                                <span class="mt-4 text-color-dark">Helpful description of the field, so your AI--}}
{{--                                    can understand what<br> this field is about and can potentially analyze or qualify--}}
{{--                                    them.</span>--}}
{{--                                <div class="input-group">--}}
{{--                                    <textarea class="form-control" id="chatDescription"--}}
{{--                                              style="background-color: #8bb1ab; color: #0f1535" rows="5"--}}
{{--                                              placeholder="Enter chat description">eg:Name of the customer</textarea>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-12 col-sm-12">--}}
{{--                                <label class="form-label mt-4 text-color-dark">Message</label><br>--}}
{{--                                <span class="mt-4 text-color-dark">How should your AI ask for this field?</span>--}}
{{--                                <div class="input-group">--}}
{{--                                    <textarea class="form-control" id="chatDescription"--}}
{{--                                              style="background-color: #8bb1ab; color: #0f1535" rows="5"--}}
{{--                                              placeholder="Enter chat description">eg:Could you please share your email so that we can follow up with you</textarea>--}}
{{--                                </div>--}}
{{--                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
{{--                                        class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">--}}
{{--                                    Add new field--}}
{{--                                </button>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                @livewire('admin.hai-chat.setting.conversation')--}}
{{--                @livewire('admin.hai-chat.setting.analytics',['name' => $lastSegment])--}}
{{--                @livewire('admin.hai-chat.setting.comparison', ['bot_name' => $lastSegment])--}}
{{--                @livewire('admin.hai-chat.setting.setting', ['bot_name' => $lastSegment])--}}

            </div>
        </div>
{{--        @include('layouts/footers/auth/footer')--}}
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
@endpush
