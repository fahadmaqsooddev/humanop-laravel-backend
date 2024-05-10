@extends('user_type.auth', ['parentFolder' => 'profile', 'childFolder' => 'none'])

@section('content')
<main class="main-content mt-1 border-radius-lg">
    <div class="container-fluid my-3 py-3 d-flex flex-column">
        <div class="row mb-5 justify-content-center align-items-center">
            <div class="col-9">
                <!-- Card Profile -->
                <div class="card card-body" id="profile" style="background-color: white">
                    <div class="row justify-content-center align-items-center">
                    <div class="col-sm-auto col-4">
                        <div class="avatar avatar-xxl position-relative">
                            <div>
                            <label for="file-input" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2">
                                <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-hidden="true" data-bs-original-title="Edit Image" aria-label="Edit Image"></i>
                                <span class="sr-only">Edit Image</span>
                            </label>
                            <span class="h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                <img src="{{ URL::asset('assets/img/users/'.auth()->user()->file) }}" class="avatar-xxl" id="imgDisplay" alt="Profile Photo">
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-auto col-8 my-auto">
                        <div class="h-100">
                        <h5 class="mb-1 font-weight-bolder" style="color: white">
                            Alec Thompson
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm" style="color: white">
                            CEO / Co-Founder
                        </p>
                        </div>
                    </div>
                        <div class="col-sm-auto ms-sm-auto mt-sm-0 mt-3 d-flex">
                            <label class="form-check-label mb-0" style="display: none">
                                <small id="profileVisibility">
                                    Switch to invisible
                                </small>
                            </label>
                            <div class="form-check form-switch ms-2" style="display: none">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault23" checked
                                       onchange="visible()">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card Basic Info -->
                <div class="card mt-4" id="basic-info" style="background-color: white">
                    <div class="card-header">
                    <h5 style="color: white">Basic Info</h5>
                    </div>
                    <div class="card-body pt-0">
                        <form action="/save-user-profile" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if($errors->get('msgError'))
                                <div class="mt-3  alert alert-primary alert-dismissible fade show" role="alert">
                                    <span class="alert-text text-white">
                                    You are in a demo version, you can't change the email address.</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </button>
                                </div>
                            @endif
                            @if(session('success'))
                                <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                                    <span class="alert-text text-white">
                                    {{ session('success') }}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </button>
                                </div>
                            @endif
                            <input type="file" id="file-input" name="user_img" accept="image/*" class="d-none">
                            <div class="row">
                                <div class="col-6">
                                <label class="form-label" style="color: white">First Name</label>
                                <div class="input-group">
                                    <input id="firstName" name="firstName" class="form-control" type="text" placeholder="Alec" required="required" value="{{ auth()->user()->first_name }}">
                                </div>
                                </div>
                                <div class="col-6">
                                <label class="form-label" style="color: white">Last Name</label>
                                <div class="input-group">
                                    <input id="lastName" name="lastName" class="form-control" type="text" placeholder="Thompson" value="{{ auth()->user()->last_name }}">
                                </div>
                                </div>
                            </div>
                            <div class="row" style="display: none">
                                <div class="col-sm-3 col-4">
                                    <label class="form-label mt-4" style="color: white">I'm</label>
                                    <select class="form-control" name="choices-gender" id="choices-gender">
                                        <option value="">Gender</option>
                                        <option value="Male" {{ $gender == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $gender == 'Female' ? 'selected=' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-sm-4 col-4">
                                            <label class="form-label mt-4" style="color: white">Birth Date</label>
                                            <select class="form-control" name="choices-month" id="choices-month">
                                                <option value="">Month</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4 col-4">
                                            <label class="form-label mt-4">&nbsp;</label>
                                            <select class="form-control" name="choices-day" id="choices-day">
                                                <option value="">Day</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4 col-4">
                                            <label class="form-label mt-4">&nbsp;</label>
                                            <select class="form-control" name="choices-year" id="choices-year">
                                                <option value="">Year</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                <label class="form-label mt-4" style="color: white">Email</label>
                                <div class="input-group">
                                    <input id="email" name="email" class="form-control" type="email" placeholder="example@email.com" value="{{ auth()->user()->email }}">
                                </div>
                                @error('email')
                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-6">
                                <label class="form-label mt-4" style="color: white">Confirmation Email</label>
                                <div class="input-group">
                                    <input id="email_confirmation" name="email_confirmation" class="form-control" type="email" placeholder="example@email.com" aria-label="email-confirmation" value="{{ old('email_confirmation') }}" aria-describedby="email-addon">
                                </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label mt-4" style="color: white; font-size: 16px;">Phone</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <h6 class="text-dark font-weight-bold text-sm mt-1">SMS Notification</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label mt-4" style="color: #575A6B">.</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <h6 class="text-dark font-weight-bold text-sm mt-1">Google 2FA</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 align-self-center">
                                    <label class="form-label mt-4" style="color: white; font-size: 16px;">HumanOp Technology</label>
                                    <div class="form-check" style="display:flex;">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <h6 class="text-dark font-weight-bold text-sm ms-3">Personal Growth and Development</h6>
                                    </div>

                                    <div class="form-check mt-2" style="display:flex;">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <h6 class="text-dark font-weight-bold text-sm ms-3">Optimize Performance and Productivity</h6>
                                    </div>

                                    <div class="form-check mt-2" style="display:flex;">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <h6 class="text-dark font-weight-bold text-sm ms-3">Business Development and Growth</h6>
                                    </div>

                                    <div class="form-check mt-2" style="display:flex;">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <h6 class="text-dark font-weight-bold text-sm ms-3">Optimize Health</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <button type="submit" class="btn bg-gradient-dark btn-sm float-end mt-6 mb-0">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@push('js')
    <script src="{{ URL::asset('assets/js/plugins/choices.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        var birthdayArray = <?PHP echo(!empty($birthdayArray) ? json_encode($birthdayArray) : '"0"'); ?>;
        var selectedYear = birthdayArray["year"];
        var selectedMonth = Math.floor(birthdayArray["month"]);
        var selectedDay = birthdayArray["day"];

        if (document.getElementById('choices-gender')) {
            var gender = document.getElementById('choices-gender');
            const example = new Choices(gender);
        }

        if (document.getElementById('choices-language')) {
            var language = document.getElementById('choices-language');
            const example = new Choices(language);
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

                if (selectedYear > 0) {
                    if (y == selectedYear) {
                        optn.selected = true;
                    }
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
                if (selectedDay > 0) {
                    if (y == selectedDay) {
                        optn.selected = true;
                    }
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
                if (selectedMonth > 0) {
                    if (optn.value == selectedMonth) {
                        optn.selected = true;
                    }
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
    </script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#imgDisplay').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#file-input").change(function () {
            readURL(this);
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#alert-success").delay(3000).slideUp(300);

        });
    </script>
@endpush
