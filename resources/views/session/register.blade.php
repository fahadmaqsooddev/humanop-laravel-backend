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
                                    <button class="btn btn-primary  p-2 border border-radius-lg border-gray-800"
                                            type="button"
                                            style="background-color: rgb(19, 21, 54);">
                                        <svg style="margin: 13px" stroke="currentColor" fill="currentColor"
                                             stroke-width="0"
                                             viewBox="0 0 488 512" aria-hidden="true"
                                             height="22px" width="22px" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z">
                                            </path>
                                        </svg>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <p class="text-center text-white"><b>or</b></p>
                        <div class="card-body">
                            <form action="{{route('store_user')}}" method="post">
                                @csrf
                                <div>
                                    <div class="">
                                        <label for="name" class="text-white">First Name</label>
                                        <input type="text" class="form-control " placeholder="first name"
                                               aria-label="Name" value="{{$google_user['first_name'] ?? ""}}"
                                               aria-describedby="email-addon" name="first_name" id="first_name"
                                               style="background-color: #0F1535; color: white; border-radius: 15px;">
                                    </div>
                                    @error('first_name')
                                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <div class="mt-3">
                                        <label for="name" class="text-white">Last Name</label>
                                        <input type="text" class="form-control " placeholder="last name"
                                               aria-label="Name" value="{{$google_user['last_name'] ?? ""}}"
                                               aria-describedby="email-addon" name="last_name" id="last_name"
                                               style="background-color: #0F1535; color: white; border-radius: 15px;">
                                    </div>
                                    @error('last_name')
                                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <div class=" ">
                                        <label for="email" class="text-white">Email</label>

                                        <input type="email" class="form-control " placeholder="Email" aria-label="Email"
                                               aria-describedby="email-addon" name="email" id="email"
                                               style="background-color: #0F1535; color: white; border-radius: 15px;" value="{{$google_user['email'] ?? ""}}" {{$google_user ? $google_user['email'] ? 'readonly' : "" : "" }}>
                                        @error('email')
                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-3" hidden>
                                    <div class=" ">
                                        <label for="email" class="text-white">Google id in case of signup from google</label>

                                        <input type="text" class="form-control"
                                               aria-describedby="email-addon" name="google_id" id="google_id"
                                               style="background-color: #0F1535; color: white; border-radius: 15px;" value="{{$google_user['google_id'] ?? ""}}">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class=" ">
                                        <label for="name" class="text-white">Password</label>
                                        <input type="password" class="form-control " placeholder="Password"
                                               aria-label="Password" aria-describedby="password-addon" name="password"
                                               id="password"
                                               style="background-color: #0F1535; color: white; border-radius: 15px;">
                                        @error('password')
                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                        @enderror
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
                                        <label for="name" class="text-white">Gender</label>
                                        <select class="form-control" name="gender" id="gender" name="user_type"
                                                style="background-color: #0F1535; color: white; border-radius: 12px;">
                                            <option value="" selected hidden>Gender At Birth</option>
                                            <option value="2">Male (XY)</option>
                                            <option value="1">Female (XX)</option>
                                        </select>
                                        @error('gender')
                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div>
                                        <label for="name" class="text-white">Age Group</label>
                                        <select class="form-control" name="age_range" id="age_range"
                                                style="background-color: #0F1535; color: white; border-radius: 12px;">
                                            <option value="5-6">5-6</option>
                                            <option value="7-11">7-11</option>
                                            <option value="12-15">12-15</option>
                                            <option value="16-20">16-20</option>
                                            <option value="21-29">21-29</option>
                                            <option value="30-33">30-33</option>
                                            <option value="34-42">34-42</option>
                                            <option value="43-51">43-51</option>
                                            <option value="52-65">52-65</option>
                                            <option value="66-69">66-69</option>
                                            <option value="70-74">70-74</option>
                                            <option value="75-83">75-83</option>
                                            <option value="84-93">84-93</option>
                                            <option value="94-101">94&up</option>
                                        </select>
                                        @error('age_range')
                                        <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                    <label style="color: rgb(160, 174, 192)" class="form-check-label" for="rememberMe">Remember
                                        me</label>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn w-100 my-4 mb-2"
                                            style="background-color: #f2661c;color:white">Sign up
                                    </button>
                                </div>
                                <p class="text-sm mt-3 mb-0" style="color: rgb(160, 174, 192)">Already have an account?
                                    <a
                                        href="{{ url('login') }}" class="text-dark font-weight-bolder">Sign in</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

{{--@push('js')--}}
{{--    <script src="{{ URL::asset('assets/js/plugins/choices.min.js') }}"></script>--}}
{{--    <script>--}}
{{--        if (document.getElementById('role_id')) {--}}
{{--            var country = document.getElementById('role_id');--}}
{{--            const example = new Choices(country);--}}
{{--            }--}}
{{--    </script>--}}
{{--@endpush--}}
