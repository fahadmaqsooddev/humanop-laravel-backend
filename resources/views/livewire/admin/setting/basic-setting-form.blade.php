<div class="card mt-4" id="basic-info">
    <div class="card-header">
        <h5 class="text-white">Basic Info</h5>
    </div>
    @include('layouts.message')
    <form wire:submit.prevent="submitForm" >
        <input type="hidden" wire:model.defer="currentUser.id">
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-6">
                    <label class="form-label text-white">First Name</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="firstName"
                               wire:model.defer="currentUser.first_name"
                               class="form-control text-white" type="text">
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label text-white">Last Name</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="lastName" wire:model.defer="currentUser.last_name"
                               class="form-control text-white" type="text" placeholder="{{$currentUser['last_name']}}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label class="form-label mt-4 text-white">Email</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="email" wire:model.defer="currentUser.email"
                               class="form-control text-white" type="email" placeholder="{{$currentUser['email']}}">
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label mt-4 text-white">Phone Number</label>
                    <div class="input-group">
                        <input style="background-color: #0f1534;" id="phone" wire:model.defer="currentUser.phone"
                               class="form-control text-white" type="text" placeholder="{{$currentUser['phone']}}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4 col-6 w-50">
                    <label class="form-label mt-4 text-white">I'm</label>
                    <select style="background-color: #0f1535" class="form-control text-white" wire:model.defer="currentUser.gender" >
                        <option value="0">Male</option>
                        <option value="1">Female</option>
                    </select>
                </div>

{{--                <div class="col-sm-4 col-6 w-50">--}}
{{--                    <label class="form-label mt-4 text-white">Age Group</label>--}}
{{--                    <select style="background-color: #0f1535" class="form-control text-white" wire:model.defer="currentUser.age_range" >--}}
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

                        <select class="justify-content-center form-control m-1" wire:model="day"
                                style="background-color: #0F1535; color: white; border-radius: 12px;">
                            <option value="">Day</option>
                            @for($i = 1; $i <= 31; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>

                        <?php
                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                            'August', 'September', 'October', 'November', 'December'];

                        $current_year = \Carbon\Carbon::now()->year;
                        ?>

                        <select class="justify-content-center form-control m-1" wire:model="month"
                                style="background-color: #0F1535; color: white; border-radius: 12px;">
                            <option value="">Month</option>
                            @foreach($months as $key => $month)
                                <option value="{{$key + 1}}">{{$month}}</option>
                            @endforeach
                        </select>

                        <select class="justify-content-center form-control m-1" wire:model="year"
                                style="background-color: #0F1535; color: white; border-radius: 12px;">
                            <option value="">Year</option>
                            @for($i = $current_year; $i >= 1970; $i--)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>

                    </div>

                    @error('date_of_birth')
                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                    @enderror
                </div>


            </div>
            <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white" style="background-color: #f2661c">Update Info</button>
        </div>

    </form>
</div>
