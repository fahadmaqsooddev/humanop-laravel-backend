@extends('user_type.guest', ['parentFolder' => 'session', 'childFolder' => 'none'])
<style>
    .left-nav-blue-light-color {
        background: #2C4C7E !important;
    }
</style>
@section('content')
    <main class="main-content  mt-0">
        <div class="page-header align-items-start section-height-50 pt-5 pb-11 m-3 border-radius-lg">
            {{-- <span class="mask bg-gradient-dark opacity-6"></span> --}}
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                        <h1 class="text-white mb-2 mt-5">Welcome!</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-6 col-lg-6 col-md-7 mx-auto">
                    <div class="card z-index-0 " style="background-color: #f3deba !important;">
                        <div class="card-header text-center pt-4">
                            <h5 style="color: #0f1535">Register with</h5>
                        </div>
                        <div class="css-mgpqwz d-flex justify-content-center">
                            <div>
                                @if(!empty($slug) && !empty($slug2))
                                    <a href="{{ url('/' . $slug. '/'. $slug2 .'/auth/google') }}">
                                        <button class="btn btn-primary bg-light p-2 border border-radius-lg border-gray-800"
                                                type="button"
                                                style="background-color: rgb(19, 21, 54);">
                                            <img src="https://www.svgrepo.com/show/303108/google-icon-logo.svg" alt=""
                                                 width="30px" ;>
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ url('auth/google') }}">
                                        <button class="btn btn-primary bg-light p-2 border border-radius-lg border-gray-800"
                                                type="button"
                                                style="background-color: rgb(19, 21, 54);">
                                            <img src="https://www.svgrepo.com/show/303108/google-icon-logo.svg" alt=""
                                                 width="30px" ;>
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>
                        @if($google_user)
                            <div class="text-center mt-0">
                                <p style="color: #0f1535; font-size: 15px">Email : {{$google_user['email']}}</p>
                            </div>
                        @endif

                        <p class="text-center" style="color: #0f1535"><b>or</b></p>
                        <div class="card-body">
                            @if(request()->segment(1) === 'register')
                                <form action="{{route('store_user')}}" method="post">
                                    @else
                                        <form
                                            action="{{\App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('register-client-to-practitioner')}}"
                                            method="post">

                                            <input type="text" class="form-control" hidden name="slug" value="{{$slug}}"
                                                   style="background-color: #f3deba; color: black; border-radius: 15px;">
                                            <input type="text" class="form-control" hidden name="slug2"
                                                   value="{{$slug2}}"
                                                   style="background-color: #f3deba; color: black; border-radius: 15px;">
                                            @endif
                                            @csrf
                                            <input type="hidden" name="referralCode" value="{{$referralCode ?? ''}}">
                                            <div>
                                                <div class="">
                                                    <label for="name" style="color: #0f1535; font-size: 15px">First
                                                        Name</label>
                                                    <input type="text" class="form-control " placeholder="first name"
                                                           aria-label="Name"
                                                           value="{{$google_user['first_name'] ?? old('first_name')}}"
                                                           aria-describedby="email-addon"
                                                           name="first_name" id="first_name"
                                                           style="background-color: #f3deba; color: black; border-radius: 15px;">
                                                </div>
                                                @error('first_name')
                                                <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <div class="mt-3">
                                                    <label for="name" style="color: #0f1535; font-size: 15px">Last
                                                        Name</label>
                                                    <input type="text" class="form-control " placeholder="last name"
                                                           aria-label="Name"
                                                           value="{{$google_user['last_name'] ?? old('last_name')}}"
                                                           aria-describedby="email-addon"
                                                           name="last_name" id="last_name"
                                                           style="background-color: #f3deba; color: black; border-radius: 15px;">
                                                </div>
                                                @error('last_name')
                                                <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mt-3" {{$google_user ? 'hidden' : ""}}>
                                                <div class=" ">
                                                    <label for="email"
                                                           style="color: #0f1535; font-size: 15px">Email</label>

                                                    <input type="email" class="form-control " placeholder="Email"
                                                           aria-label="Email"
                                                           aria-describedby="email-addon" name="email" id="email"
                                                           style="background-color: #f3deba; color: black; border-radius: 15px;"
                                                           value="{{$google_user['email'] ?? old('email')}}" {{$google_user ? $google_user['email'] ? 'readonly' : "" : "" }}>
                                                    @error('email')
                                                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mt-3" hidden>
                                                <div class=" ">
                                                    <label for="email" style="color: #0f1535; font-size: 15px">Google id
                                                        in case of
                                                        signup from
                                                        google</label>

                                                    <input type="text" class="form-control"
                                                           aria-describedby="email-addon"
                                                           name="google_id" id="google_id"
                                                           style="background-color: #f3deba; color: black; border-radius: 15px;"
                                                           value="{{$google_user['google_id'] ?? ""}}">
                                                </div>
                                            </div>
                                            <div class="mt-3 position-relative" {{$google_user ? 'hidden' : ""}}>
                                                <div>
                                                    <label for="password" style="color: #0f1535; font-size: 15px">Password</label>
                                                    <input type="password" class="form-control" placeholder="Password"
                                                           aria-label="Password" aria-describedby="password-addon"
                                                           name="password"
                                                           id="password"
                                                           style="background-color: #f3deba; color: black; border-radius: 15px;">
                                                    <!-- Eye icon for toggling password visibility -->
                                                    <span class="position-absolute" id="togglePassword"
                                                          style="right: 15px; top: 50px; transform: translateY(-50%); cursor: pointer; color: white; z-index: 10;">
                                        <i class="fas fa-eye pt-1 password-eye" id="password-eye"
                                           style="    color: #f2661c !important;"></i>
                                    </span>
                                                    <div id="validatePassword"></div>
                                                    @error('password')
                                                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mt-3 position-relative" {{$google_user ? 'hidden' : ""}}>
                                                <div>
                                                    <label for="password" style="color: #0f1535; font-size: 15px">Confirm
                                                        Password</label>
                                                    <input type="password" class="form-control"
                                                           placeholder="Confirm Password"
                                                           aria-label="Password" aria-describedby="password-addon"
                                                           name="password_confirmation"
                                                           id="confirmPassword"
                                                           style="background-color: #f3deba; color: black; border-radius: 15px;">
                                                    <div id="validateConfirmPassword"></div>
                                                    @error('password_confirmation')
                                                     <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                                    @enderror
                                                <!-- Eye icon for toggling password visibility -->
                                                    <span class="position-absolute" id="toggleConfirmPassword"
                                                          style="right: 15px; top: 50px; transform: translateY(-50%); cursor: pointer; color: white; z-index: 10;">
                                        <i class="fas fa-eye pt-1 confirm-password-eye" id="confirm-password-eye"
                                           style="    color: #f2661c !important;"></i>
                                    </span>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <div>
                                                    <label for="name"
                                                           style="color: #0f1535; font-size: 15px">Phone</label>
                                                    <input type="tel" class="form-control"
                                                           placeholder="+1 (123) 456-7890"
                                                           aria-label="Phone"
                                                           name="phone" value="{{old('phone')}}" id="phone" maxlength="14"
                                                           title="Phone number should be in the format +1XXXXXXXXXX or XXXXXXXXXX"
                                                           style="background-color: #f3deba; color: black; border-radius: 15px;">
                                                    @error('phone')
                                                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <div class="">
                                                    <label for="name" style="color: #0f1535; font-size: 15px">Gender at
                                                        Birth</label>
                                                    <select class="form-control" name="gender"  id="gender"
                                                            style="background-color: #f3deba; color: black; border-radius: 15px;">
                                                        <option value="" selected hidden>Gender at Birth
                                                        </option>
                                                        <option value="0" {{old('gender') == 0 ? 'selected' : ''}}>Male (XY)</option>
                                                        <option value="1" {{old('gender') == 1 ? 'selected' : ''}}>Female (XX)</option>
                                                    </select>
                                                    @error('gender')
                                                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <div>
                                                    <label for="name" style="color: #0f1535; font-size: 15px">Date of
                                                        Birth</label>

                                                    <div class="d-flex w-100">

                                                        <?php
                                                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                                                            'August', 'September', 'October', 'November', 'December'];

                                                        $current_year = (int)(\Carbon\Carbon::now()->year - 18);
                                                        ?>
                                                            <div class="flex-fill" style="margin-right: 10px" >
                                                        <select class="justify-content-center form-control m-2"
                                                                name="month"
                                                                id="month"
                                                                style="background-color: #f3deba; color: black; border-radius: 15px;">
                                                            <option value="">Month</option>
                                                            @foreach($months as $key => $month)
                                                                <option value="{{$key + 1}}" {{old('month') == $key + 1 ? 'selected' : ''}}>{{$month}}</option>
                                                            @endforeach
                                                        </select>
                                                            </div>
                                                                <div class="flex-fill " style="margin-right: 10px">
                                                        <select class="justify-content-center form-control m-2"
                                                                name="day"
                                                                style="background-color: #f3deba; color: black; border-radius: 15px;" id="day">
                                                            <option value="">Day</option>
                                                            @for($i = 1; $i <= 31; $i++)
                                                                <option value="{{$i}}" {{old('day') == $i ? 'selected' : ''}} >{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                                </div>
                                                            <div class="flex-fill">
                                                        <select class="justify-content-center form-control m-2" id="year"
                                                                name="year"
                                                                style="background-color: #f3deba; color: black; border-radius: 15px;">
                                                            <option value="">Year</option>
                                                            @for($i = $current_year; $i >= 1900; $i--)
                                                                <option
                                                                    value="{{$i}}" {{old('year') == $i  ? 'selected' : ''}}>{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                            </div>

                                                    </div>

                                                    @error('date_of_birth')
                                                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mt-3">
{{--                                                <label for="name" style="color: #0f1535; font-size: 17px">Your Intention for using the HumanOp® Technology</label><br>--}}
                                                <label for="name" style="color: #f2661c; font-size: 15px">Your Intention for using the HumanOp® Technology</label>
                                                @foreach($intention_options as $option)
                                                    <div class="form-check">
                                                        <input type="checkbox" name="ninety_day_intention[]"
                                                               value="{{$option['id']}}" class="form-check-input"  {{ is_array(old('ninety_day_intention')) && in_array($option['id'], old('ninety_day_intention')) ? 'checked' : '' }} >
                                                        <label for="name"
                                                               style="color: #0f1535; font-size: 15px" >{{$option['description']}}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="form-check form-switch mt-4">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                       id="rememberMe" {{old('remember') ? 'checked' : ''}}>
                                                <label style="color: #0f1535" class="form-check-label"
                                                       for="rememberMe">Remember
                                                    me</label>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn w-100 my-4 mb-2"
                                                        style="background-color: #f2661c;color:white">Sign up
                                                </button>
                                            </div>
                                            <p class="text-sm mt-3 mb-0" style="color: #0f1535">Already have an account?
                                                @if(request()->segment(1) === 'register')
                                                    <a href="{{ url('login') }}" class=" font-weight-bolder"
                                                       style="color: #0f1535">Sign
                                                        in</a>
                                                @else
                                                    <a href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('login')}}"
                                                       class=" font-weight-bolder" style="color: #0f1535">Sign
                                                        in</a>
                                                @endif
                                            </p>
                                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

<script>
    // Ensure the DOM is fully loaded before running the script
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('password-eye');

        // Add click event listener to the eye icon
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default behavior (especially for anchors)

                // Toggle the password visibility and the eye icon
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash'); // Change to the eye-slash icon when password is visible
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye'); // Change back to the eye icon when password is hidden
                }
            });
        }


        // confirm password eye toggler
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const confirmIcon = document.getElementById('confirm-password-eye');

        // Add click event listener to the eye icon
        if (toggleConfirmPassword && confirmPasswordInput) {
            toggleConfirmPassword.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default behavior (especially for anchors)

                // Toggle the password visibility and the eye icon
                if (confirmPasswordInput.type === 'password') {
                    confirmPasswordInput.type = 'text';
                    confirmIcon.classList.remove('fa-eye');
                    confirmIcon.classList.add('fa-eye-slash'); // Change to the eye-slash icon when password is visible
                } else {
                    confirmPasswordInput.type = 'password';
                    confirmIcon.classList.remove('fa-eye-slash');
                    confirmIcon.classList.add('fa-eye'); // Change back to the eye icon when password is hidden
                }
            });
        }
    });


    //form validation code
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const fields = {
            first_name: {
                element: document.getElementById("first_name"),
                errorMessage: "First name is required",
            },
            last_name: {
                element: document.getElementById("last_name"),
                errorMessage: "Last name is required",
            },
            email: {
                element: document.getElementById("email"),
                errorMessage: "Please enter a valid email",
            },
            password: {
                element: document.getElementById("password"),
                errorMessage: "Password must be at least 6 characters",
            },
            confirmPassword: {
                element: document.getElementById("confirmPassword"),
                errorMessage: "Passwords do not match",
            },
            // phone: {
            //     element: document.getElementById("phone"),
            //     errorMessage: "Phone is required",
            // },
            gender: {
                element: document.getElementById("gender"),
                errorMessage: "Please select your gender",
            },
            year : {
                element: document.getElementById("year"),
                errorMessage: "Select year",
            },
            month : {
                element: document.getElementById("month"),
                errorMessage: "Select month",
            },
            day : {
                element: document.getElementById("day"),
                errorMessage: "Select day",
            }
        };

        // Function to validate individual fields
        function validateField(field, value) {
            let isValid = true;
            let message = "";

            // Validation conditions
            if (value.trim() === "") {
                isValid = false;
                message = field.errorMessage;
            } else if (field.element.id === "email" && !/^\S+@\S+\.\S+$/.test(value)) {
                isValid = false;
                message = field.errorMessage;
            } else if (field.element.id === "password" && value.length < 6) {
                isValid = false;
                message = field.errorMessage;
            } else if (field.element.id === "confirmPassword" && value !== fields.password.element.value) {
                isValid = false;
                message = field.errorMessage;
            } else if (field.element.id === "day" && field.element.value === "") {
                isValid = false;
                message = field.errorMessage; // Assuming errorMessage is set properly for each field
            } else if (field.element.id === "month" && field.element.value === "") {
                isValid = false;
                message = field.errorMessage;
            } else if (field.element.id === "year" && field.element.value === "") {
                isValid = false;
                message = field.errorMessage;
            }


            // Get or create error message container
            let errorElement = field.element.nextElementSibling;
            if (!errorElement || !errorElement.classList.contains('error-message')) {
                errorElement = document.createElement("p");
                errorElement.className = "text-danger error-message mt-2 mb-2";
                field.element.insertAdjacentElement("afterend", errorElement);
            }

            // Show or hide the error message
            errorElement.textContent = isValid ? "" : message;

            return isValid;
        }

        // Event listeners for real-time validation
        Object.keys(fields).forEach(key => {
            const field = fields[key];
            field.element.addEventListener("input", (event) => {
                validateField(field, event.target.value);
            });
        });

        // Form submit validation
        form.addEventListener("submit", function (e) {
            let formIsValid = true;

            // Check each field on form submission
            Object.keys(fields).forEach(key => {
                const field = fields[key];
                const isValid = validateField(field, field.element.value);
                if (!isValid) formIsValid = false;
            });

            if (!formIsValid) {
                e.preventDefault(); // Prevent form submission if any field is invalid
            }
        });
    });


    // function disableBack() { window.history.forward(); }
    // setTimeout("disableBack()", 0);
</script>
