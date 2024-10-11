<div class="card setting-box-background mt-4" id="add-sub-admin-info">
    <div class="card-header">
        <h5 class="text-white">Add Sub Admin</h5>
    </div>
    @include('layouts.message')
    <form wire:submit.prevent="submitForm" >
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-6">
                    <label class="form-label text-white">First Name</label>
                    <div class="input-group">
                        <input id="firstName"
                               wire:model.defer="sub_admin.first_name"
                               class="form-control setting-box-background" type="text" placeholder="First Name" >
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label text-white">Last Name</label>
                    <div class="input-group">
                        <input id="lastName" wire:model.defer="sub_admin.last_name"
                               class="form-control setting-box-background" type="text" placeholder="Last Name">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label class="form-label mt-4 text-white">Email</label>
                    <div class="input-group">
                        <input id="email" wire:model.defer="sub_admin.email"
                               class="form-control setting-box-background" type="email" placeholder="email">
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label mt-4 text-white">Phone Number</label>
                    <div class="input-group">
                        <input id="phone" wire:model.defer="sub_admin.phone"
                               class="form-control setting-box-background" type="text" placeholder="phone">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label class="form-label mt-4 text-white">Password</label>
                    <div class="input-group">
                        <input wire:model="sub_admin.password" class="form-control setting-box-background" type="password"
                               placeholder="Current password">
                    </div>
                </div>
                <div class="col-sm-4 col-6 w-50">
                    <label class="form-label mt-4 text-white">I'm</label>
                    <select class="form-control setting-box-background" wire:model.defer="sub_admin.gender" >
                        <option value="0">Male</option>
                        <option value="1">Female</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 col-6 w-50">
                    <label for="name" class="text-white mt-4">Date of Birth</label>

                    <div class="d-flex w-100">

                        <?php
                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                            'August', 'September', 'October', 'November', 'December'];

                        $current_year = (int)(\Carbon\Carbon::now()->year - 18);
                        ?>

                        <select class="justify-content-center form-control m-1 setting-box-background" wire:model="month">
                            <option value="">Month</option>
                            @foreach($months as $key => $month)
                                <option value="{{$key + 1}}">{{$month}}</option>
                            @endforeach
                        </select>

                        <select class="justify-content-center form-control m-1 setting-box-background" wire:model="day">
                            <option value="">Day</option>
                            @for($i = 1; $i <= 31; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>

                        <select class="justify-content-center form-control m-1 setting-box-background" wire:model="year">
                            <option value="">Year</option>
                            @for($i = $current_year; $i >= 1900; $i--)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>

                    </div>

                    @error('date_of_birth')
                    <p class="text-danger text-xs mt-2 mb-2">{{ $message }}</p>
                    @enderror
                </div>
{{--            <div class="col-sm-4 col-6 w-50">--}}
{{--                <label class="form-label mt-4">Age Group</label>--}}
{{--                <select style="background-color: #0f1535" class="form-control" wire:model.defer="sub_admin.age_range" >--}}
{{--                    <option value="5-6">5-6</option>--}}
{{--                    <option value="7-11">7-11</option>--}}
{{--                    <option value="12-15">12-15</option>--}}
{{--                    <option value="16-20">16-20</option>--}}
{{--                    <option value="21-29">21-29</option>--}}
{{--                    <option value="30-33">30-33</option>--}}
{{--                    <option value="34-42">34-42</option>--}}
{{--                    <option value="43-51">43-51</option>--}}
{{--                    <option value="52-65">52-65</option>--}}
{{--                    <option value="66-69">66-69</option>--}}
{{--                    <option value="70-74">70-74</option>--}}
{{--                    <option value="75-83">75-83</option>--}}
{{--                    <option value="84-93">84-93</option>--}}
{{--                    <option value="94-101">94&up</option>--}}
{{--                </select>--}}
{{--            </div>--}}
                <p class="text-muted mt-4 mb-2">
                    Please assign permissions to Sub Admin:
                </p>
                <div class="row">
                    <div class="col-sm-4 col-6 w-50">
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.users">
                        <label class="form-check-label text-white">Client</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.deletedClient">
                        <label class="form-check-label text-white">Deleted Client</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.clientQueries">
                        <label class="form-check-label text-white">Client Queries</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.questions">
                        <label class="form-check-label text-white">Questions</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.resources">
                        <label class="form-check-label text-white">Resources</label>
                    </div>
                    <div class="col-sm-4 col-6 w-50" style="padding-left: 27px">
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.cms">
                        <label class="form-check-label text-white">CMS</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.chat">
                        <label class="form-check-label text-white">H.A.I Chat</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.projects">
                        <label class="form-check-label text-white">Projects</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.abandonedAssessment">
                        <label class="form-check-label text-white">Abandoned Assessment</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.approveQueries">
                        <label class="form-check-label text-white">Approve Queries</label>
                    </div>
                </div>

            </div>
            <button type="submit" class="rainbow-border-user-nav-btn btn-sm float-end mt-4 mb-4">
                Create Sub Admin
            </button>
            <p class="text-muted mt-4 mb-2">
                Please follow this guide for a strong password:
            </p>

            <ul class="text-muted ps-4 mb-3 float-start">
                <li>
                    <span class="text-sm text-white">One special characters</span>
                </li>
                <li>
                    <span class="text-sm text-white">Min 6 characters</span>
                </li>
                <li>
                    <span class="text-sm text-white">One number (2 are recommended)</span>
                </li>
                <li>
                    <span class="text-sm text-white">Change it often</span>
                </li>
            </ul>

        </div>

    </form>
</div>

