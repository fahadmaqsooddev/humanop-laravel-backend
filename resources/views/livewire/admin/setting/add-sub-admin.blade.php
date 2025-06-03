<div class="card setting-box-background mt-4" id="add-sub-admin-info">
    <div class="card-header">
        <h5 class="text-color-dark setting-form-heading">Add Sub Admin</h5>
    </div>
    @include('layouts.message')
    <form wire:submit.prevent="submitForm">
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-6">
                    <label class="form-label text-color-dark">First Name</label>
                    <div class="input-group">
                        <input id="firstName"
                               wire:model.defer="sub_admin.first_name"
                               class="form-control setting-box-background" type="text" placeholder="First Name">
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label text-color-dark">Last Name</label>
                    <div class="input-group">
                        <input id="lastName" wire:model.defer="sub_admin.last_name"
                               class="form-control setting-box-background" type="text" placeholder="Last Name">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label class="form-label mt-4 text-color-dark">Email</label>
                    <div class="input-group">
                        <input id="email" wire:model.defer="sub_admin.email"
                               class="form-control setting-box-background" type="Email" placeholder="email">
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label mt-4 text-color-dark">Phone Number</label>
                    <div class="input-group">
                        <input id="phone" wire:model.defer="sub_admin.phone"
                               class="form-control setting-box-background" type="text" placeholder="Phone #">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label class="form-label mt-4 text-color-dark">Password</label>
                    <div class="input-group">
                        <input wire:model="sub_admin.password" class="form-control setting-box-background"
                               type="password"
                               placeholder="Password">
                    </div>
                </div>
                <div class="col-sm-4 col-6 w-50">
                    <label class="form-label mt-4 text-color-dark">I'm</label>
                    <select class="form-control setting-box-background text-color-dark"
                            wire:model.defer="sub_admin.gender">
                        <option value="0">Male [XY]</option>
                        <option value="1">Female [XX]</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 col-6 w-50">
                    <label for="name" class="text-color-dark mt-4">Date of Birth</label>
                    <div class="d-flex w-100">
                        <?php
                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                            'August', 'September', 'October', 'November', 'December'];

                        $current_year = (int)(\Carbon\Carbon::now()->year - 7);
                        ?>
                        <select class="justify-content-center form-control m-1 setting-box-background text-color-dark"
                                wire:model="month">
                            <option value="">Month</option>
                            @foreach($months as $key => $month)
                                <option value="{{$key + 1}}">{{$month}}</option>
                            @endforeach
                        </select>
                        <select class="justify-content-center form-control m-1 setting-box-background text-color-dark"
                                wire:model="day">
                            <option value="">Day</option>
                            @for($i = 1; $i <= 31; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                        <select class="justify-content-center form-control m-1 setting-box-background text-color-dark"
                                wire:model="year">
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
                <p class="text-muted mt-4 mb-2">
                    Please assign permissions to Sub Admin:
                </p>
                <div class="row">
                    <div class="col-sm-4 col-6 w-50">
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.user_management">
                        <label class="form-check-label text-color-dark">User Management</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.assessment_management">
                        <label class="form-check-label text-color-dark">Assessment Management</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.technology_management">
                        <label class="form-check-label text-color-dark">Technology Management</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.team_management">
                        <label class="form-check-label text-color-dark">Team Management</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.hai_admin">
                        <label class="form-check-label text-color-dark">HAi Admin</label>

                    </div>
                    <div class="col-sm-4 col-6 w-50" style="padding-left: 27px">
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.cms_admin">
                        <label class="form-check-label text-color-dark">CMS Admin</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.support_admin">
                        <label class="form-check-label text-color-dark">Support Admin</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.client_queries">
                        <label class="form-check-label text-color-dark">Client Queries</label>
                        <br>
                        <input type="checkbox"
                               class="form-check-input"
                               wire:model.defer="permission.approve_queries">
                        <label class="form-check-label text-color-dark">Approve Queries</label>
                        <br>
                    </div>
                </div>
            </div>
            <button type="submit" class=" btn-sm float-end mt-4 mb-4"
                    style="background:#1b3a62 !important;color:white;font-weight:bolder;border:none;">
                Create Sub Admin
            </button>
        </div>
    </form>
</div>

