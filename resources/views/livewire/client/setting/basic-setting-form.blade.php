<div class="card mt-4 setting-box-background" id="basic-info">
    <div class="card-header">
        <h5 class="text-white">Basic Info</h5>
    </div>
    @include('layouts.message')
    <form wire:submit.prevent="submitForm" >
        <input type="hidden" wire:model.defer="user.id">
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <label class="form-label text-white">First Name</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="firstName"
                               wire:model.defer="user.first_name"
                               class="form-control text-white" type="text">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <label class="form-label  mt-4 mt-md-0 text-white">Last Name</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="lastName" wire:model.defer="user.last_name"
                               class="form-control text-white" type="text" placeholder="{{$user['last_name']}}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <label class="form-label mt-4 text-white">Email</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="email" wire:model.defer="user.email"
                               class="form-control text-white" type="email" placeholder="Enter your email" readonly>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <label class="form-label mt-4 text-white">Phone Number</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="phone" wire:model.defer="user.phone"
                               class="form-control text-white" type="text" placeholder="Enter your phone number">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4 col-6 w-50">
                    <label class="form-label mt-4 text-white">I'm</label>
                    <select class="form-control text-white" id="client-gender" style="background-color: #0f1534;" wire:model.defer="user.gender">
                        <option>Select Gender</option>
                        <option value="0">Male</option>
                        <option value="1">Female</option>
                    </select>
                </div>

                <input type="text" wire:model="is_abandon_assessment" id="is_abandon_assessment" hidden>

{{--                <div class="col-sm-4 col-6 w-50">--}}
{{--                    <label class="form-label mt-4 text-white">Age Group</label>--}}
{{--                    <select class="form-control text-white" style="background-color: #0f1534;" wire:model.defer="user.age_range" >--}}
{{--                        <option>Select Age</option>--}}
{{--                        <option value="5-6">5-6</option>--}}
{{--                        <option value="7-11">7-11</option>--}}
{{--                        <option value="12-15">12-15</option>--}}
{{--                        <option value="16-20">16-20</option>--}}
{{--                        <option value="21-29">21-29</option>--}}
{{--                        <option value="30-33">30-33</option>--}}
{{--                        <option value="34-42">34-42</option>--}}
{{--                        <option value="43-51">43-51</option>--}}
{{--                        <option value="52-65">52-65</option>--}}
{{--                        <option value="66-69">66-69</option>--}}
{{--                        <option value="70-74">70-74</option>--}}
{{--                        <option value="75-83">75-83</option>--}}
{{--                        <option value="84-93">84-93</option>--}}
{{--                        <option value="94-101">94&up</option>--}}
{{--                    </select>--}}
{{--                </div>--}}

                <div class="col-sm-4 col-6 w-50">
                    <label for="name" class="text-white mt-4">Date of Birth</label>

                    <div class="d-flex w-100">

                        <?php
                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                            'August', 'September', 'October', 'November', 'December'];

                        $current_year = (int)(\Carbon\Carbon::now()->year - 18);
                        ?>

                        <select class="justify-content-center form-control m-1" wire:model="month"
                                style="background-color: #0F1535; color: white; border-radius: 12px;">
                            <option value="">Month</option>
                            @foreach($months as $key => $month)
                                <option value="{{$key + 1}}" {{isset($date_of_birth[1]) && $date_of_birth[1] == ($key+1) ? 'selected' : '' }} >{{$month}}</option>
                            @endforeach
                        </select>

                        <select class="justify-content-center form-control m-1" wire:model="day"
                                style="background-color: #0F1535; color: white; border-radius: 12px;">
                            <option value="">Day</option>
                            @for($i = 1; $i <= 31; $i++)
                                <option value="{{$i}}" {{isset($date_of_birth[2]) && $date_of_birth[2] == $i ? 'selected' : '' }}>{{$i}}</option>
                            @endfor
                        </select>

                        <select class="justify-content-center form-control m-1" wire:model="year"
                                style="background-color: #0F1535; color: white; border-radius: 12px;">
                            <option value="">Year</option>
                            @for($i = $current_year; $i >= 1900; $i--)
                                <option value="{{$i}}" {{isset($date_of_birth[0]) && $date_of_birth[0] == $i ? 'selected' : '' }}>{{$i}}</option>
                            @endfor
                        </select>

                    </div>

                    @error('date_of_birth')
                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-md-6 col-sm-12">
                    <label class="form-label mt-4 text-white">Profile Image</label>
                    <input type="file" wire:model="profile_image" style="background-color: #0f1534;" class="form-control">
                </div>
            </div>
            <button type="submit" class=" btn-sm  float-end mt-4 mb-3 rainbow-border-user-nav-btn" >Update Info</button>
        </div>

    </form>
</div>
@push('javascript')
<script>
    window.livewire.on('userBasicSettingUpdated', updatedUser => {
        $('#profile_image').attr('src',updatedUser.photo_url.url);
        $('#firstname').text(updatedUser.first_name);
        $('#lastname').text(updatedUser.last_name);
        $('#email').text(updatedUser.email);
    });


    (function () {
        var previous, is_abandon_assessment;

        $("#client-gender").on('focus', function () {

            // Store the current value on focus and on change
            previous = this.value;

            is_abandon_assessment = $('#is_abandon_assessment').val();

        }).change(function() {
            // Do something with the previous value after the change

            if (is_abandon_assessment !== "false"){

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn bg-gradient-danger m-2',
                        cancelButton:  'btn bg-gradient-primary m-2',
                    },
                    buttonsStyling: false,
                    background : '#3442b4',
                })
                swalWithBootstrapButtons.fire({
                    title: '<span style="color: white;">Are you sure?</span>',
                    html: "<span style='color: white;'>Want to change gender and that resets your incomplete assessment</span>",
                    showCancelButton: true,
                    confirmButtonText: 'Reset data',
                }).then((result) => {

                    if(result.isConfirmed){

                        window.livewire.emit('deleteAbandonAssessmentOnGenderChange')

                    }else{

                        $('#client-gender').val(previous);
                    }
                })

            }

        });
    })();

    </script>
@endpush
