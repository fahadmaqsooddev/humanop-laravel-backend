@extends('user_type.guest', ['parentFolder' => 'session', 'childFolder' => 'none'])

@section('content')
<main class="main-content  mt-0">
    <div class="page-header align-items-start section-height-50 pt-5 pb-11 m-3 border-radius-lg"
        style="background-image: url('assets/img/login.webp');">
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
            <div class="col-xl-5 col-lg-5 col-md-7 mx-auto">
                <div class="card z-index-0">
                    <div class="card-header text-center pt-4">
                        <h5 class="text-white">Register with</h5>
                    </div>
                    <div class="css-mgpqwz d-flex justify-content-center">
                        <div>
                            <a href="{{ url('auth/google') }}">
                                <button class="btn btn-primary bg-light p-2 border border-radius-lg border-gray-800"
                                    type="button" style="background-color: rgb(19, 21, 54);">
                                    <img src="https://www.svgrepo.com/show/303108/google-icon-logo.svg" alt=""
                                        width="35px">
                                </button>
                            </a>
                        </div>
                    </div>
                    @if($google_user)
                    <div class="text-center mt-0">
                        <p>Email : {{$google_user['email']}}</p>
                    </div>
                    @endif

                    <p class="text-center text-white"><b>or</b></p>
                    <div class="card-body">
                        <form action="{{route('store_user')}}" method="post">
                            @csrf
                            <input type="hidden" name="referralCode" value="{{$referralCode ?? ''}}">
                            <div>
                                <div class="">
                                    <label for="name" class="text-white">First Name</label>
                                    <input type="text" class="form-control " placeholder="first name" aria-label="Name"
                                        value="{{$google_user['first_name'] ?? ""}}" aria-describedby="email-addon"
                                        name="first_name" id="first_name"
                                        style="background-color: #0F1535; color: white; border-radius: 15px;">
                                </div>
                                @error('first_name')
                                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <div class="mt-3">
                                    <label for="name" class="text-white">Last Name</label>
                                    <input type="text" class="form-control " placeholder="last name" aria-label="Name"
                                        value="{{$google_user['last_name'] ?? ""}}" aria-describedby="email-addon"
                                        name="last_name" id="last_name"
                                        style="background-color: #0F1535; color: white; border-radius: 15px;">
                                </div>
                                @error('last_name')
                                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-3" {{$google_user ? 'hidden' : ""}}>
                                <div class=" ">
                                    <label for="email" class="text-white">Email</label>

                                    <input type="email" class="form-control " placeholder="Email" aria-label="Email"
                                        aria-describedby="email-addon" name="email" id="email"
                                        style="background-color: #0F1535; color: white; border-radius: 15px;"
                                        value="{{$google_user['email'] ?? ""}}" {{$google_user ? $google_user['email'] ? 'readonly' : "" : "" }}>
                                    @error('email')
                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-3" hidden>
                                <div class=" ">
                                    <label for="email" class="text-white">Google id in case of signup from
                                        google</label>

                                    <input type="text" class="form-control" aria-describedby="email-addon"
                                        name="google_id" id="google_id"
                                        style="background-color: #0F1535; color: white; border-radius: 15px;"
                                        value="{{$google_user['google_id'] ?? ""}}">
                                </div>
                            </div>
                            <div class="mt-3 position-relative" {{$google_user ? 'hidden' : ""}}>
                                <div>
                                    <label for="password" class="text-white">Password</label>
                                    <input type="password" class="form-control" placeholder="Password"
                                        aria-label="Password" aria-describedby="password-addon" name="password"
                                        id="password"
                                        style="background-color: #0F1535; color: white; border-radius: 15px; padding-right: 40px;">
                                    @error('password')
                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                    @enderror
                                    <!-- Eye icon for toggling password visibility -->
                                    <span class="position-absolute" id="togglePassword"
                                        style="right: 15px; top: 65%; transform: translateY(-50%); cursor: pointer; color: white; z-index: 10;">
                                        <i class="fas fa-eye pt-1 password-eye" id="password-eye"
                                            style="    color: #f2661c !important;"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mt-3 position-relative" {{$google_user ? 'hidden' : ""}}>
                                <div>
                                    <label for="password" class="text-white">Confirm Password</label>
                                    <input type="password" class="form-control" placeholder="Confirm Password"
                                           aria-label="Password" aria-describedby="password-addon" name="password_confirmation"
                                           id="confirmPassword"
                                           style="background-color: #0F1535; color: white; border-radius: 15px; padding-right: 40px;">
                                    @error('password_confirmation')
                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                    @enderror
                                <!-- Eye icon for toggling password visibility -->
                                    <span class="position-absolute" id="toggleConfirmPassword"
                                          style="right: 15px; top: 65%; transform: translateY(-50%); cursor: pointer; color: white; z-index: 10;">
                                        <i class="fas fa-eye pt-1 confirm-password-eye" id="confirm-password-eye"
                                           style="    color: #f2661c !important;"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div>
                                    <label for="name" class="text-white">Phone</label>
                                    <input type="tel" class="form-control" placeholder="Phone" aria-label="Phone"
                                        name="phone" id="phone"
                                        style="background-color: #0F1535; color: white; border-radius: 15px;">
                                    @error('phone')
                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="">
                                    <label for="name" class="text-white">Gender at Birth</label>
                                    <select class="form-control" name="gender" id="gender" name="user_type"
                                        style="background-color: #0F1535; color: white; border-radius: 12px;">
                                        <option value="" selected hidden>Gender at Birth
                                        </option>
                                        <option value="0">Male (XY)</option>
                                        <option value="1">Female (XX)</option>
                                    </select>
                                    @error('gender')
                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
{{--                            <div class="mt-3">--}}
{{--                                <div>--}}
{{--                                    <label for="name" class="text-white">Age Group</label>--}}
{{--                                    <select class="form-control" name="age_range" id="age_range"--}}
{{--                                        style="background-color: #0F1535; color: white; border-radius: 12px;">--}}
{{--                                        <option value="5-6">5-6</option>--}}
{{--                                        <option value="7-11">7-11</option>--}}
{{--                                        <option value="12-15">12-15</option>--}}
{{--                                        <option value="16-20">16-20</option>--}}
{{--                                        <option value="21-29">21-29</option>--}}
{{--                                        <option value="30-33">30-33</option>--}}
{{--                                        <option value="34-42">34-42</option>--}}
{{--                                        <option value="43-51">43-51</option>--}}
{{--                                        <option value="52-65">52-65</option>--}}
{{--                                        <option value="66-69">66-69</option>--}}
{{--                                        <option value="70-74">70-74</option>--}}
{{--                                        <option value="75-83">75-83</option>--}}
{{--                                        <option value="84-93">84-93</option>--}}
{{--                                        <option value="94-101">94&up</option>--}}
{{--                                    </select>--}}
{{--                                    @error('age_range')--}}
{{--                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="mt-3">
                                <div>
                                    <label for="name" class="text-white">Date of Birth</label>

                                    <div class="d-flex w-100">

                                        <?php
                                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                                            'August', 'September', 'October', 'November', 'December'];

                                        $current_year = (int)(\Carbon\Carbon::now()->year - 18);
                                        ?>

                                        <select class="justify-content-center form-control m-2" name="month"
                                                style="background-color: #0F1535; color: white; border-radius: 12px;">
                                            <option value="">Month</option>
                                            @foreach($months as $key => $month)
                                                <option value="{{$key + 1}}">{{$month}}</option>
                                            @endforeach
                                        </select>

                                        <select class="justify-content-center form-control m-2" name="day"
                                                style="background-color: #0F1535; color: white; border-radius: 12px;">
                                            <option value="">Day</option>
                                            @for($i = 1; $i <= 31; $i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>

                                        <select class="justify-content-center form-control m-2" name="year"
                                                style="background-color: #0F1535; color: white; border-radius: 12px;">
                                            <option value="">Year</option>
                                            @for($i = $current_year; $i >= 1900; $i--)
                                                <option value="{{$i}}" {{$i === 1980 ? 'selected' : ''}}>{{$i}}</option>
                                            @endfor
                                        </select>

                                    </div>

                                    @error('date_of_birth')
                                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="name" class="text-white">90 Day Intention</label>
                                <input type="text" class="form-control " placeholder="In the next 90 Days I would like to ..." name="ninety_day_intention"
                                       style="background-color: #0F1535; color: white; border-radius: 15px;">
                            </div>
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                <label style="color: rgb(160, 174, 192)" class="form-check-label"
                                    for="rememberMe">Remember
                                    me</label>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn w-100 my-4 mb-2"
                                    style="background-color: #f2661c;color:white">Sign up
                                </button>
                            </div>
                            <p class="text-sm mt-3 mb-0" style="color: rgb(160, 174, 192)">Already have an account?
                                <a href="{{ url('login') }}" class="text-dark font-weight-bolder">Sign in</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

{{--@push('js')--}}
{{--
<script src="{{ URL::asset('assets/js/plugins/choices.min.js') }}"></script>--}}
{{--
<script>--}}
    <!-- {
        { --        if (document.getElementById('role_id')) { --} }
        { { --            var country = document.getElementById('role_id'); --} }
        { { --            const example = new Choices(country); --} }
        { { --            } --}
    }
    {
        {
            --    </script>--}} -->
{{--@endpush--}}

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

                // function disableBack() { window.history.forward(); }
                // setTimeout("disableBack()", 0);
</script>
