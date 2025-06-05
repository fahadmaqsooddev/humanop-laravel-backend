<div>
    <form wire:submit.prevent="updatePermission">
        @csrf
        @if(session('success'.$adminId))
            <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{session('success'.$adminId) }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="fa fa-close" aria-hidden="true"></i>
                </button>
            </div>
        @elseif(session('error'.$adminId))
            <div class="m-3  alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                                        <span class="alert-text text-white">
                                            {{session('error'.$adminId) }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="fa fa-close" aria-hidden="true"></i>
                </button>
            </div>
        @endif
        <div class="card-body">
            <div class="row">
                <h5 class="text-bold">Please assign permissions to Sub Admin for B2C Admin Dashboard:</h5>
                <div class="col-sm-4 col-6 w-50">
                    @foreach(['user_management', 'assessment_management','technology_management', 'team_management'] as $permissionName)
                        <input type="checkbox" style="border: 2px solid #1b3a62" class="form-check-input" wire:model="permission" value="{{$permissionName}}">
                        <label class="form-check-label" style="color: #1B3A62">@if($permissionName === 'hai_admin')
                                HAi Admin
                            @else
                                {{ ucwords(str_replace('_', ' ', $permissionName)) }}
                            @endif</label>
                        <br>
                    @endforeach
                </div>
                <div class="col-sm-4 col-6 w-50" style="padding-left: 27px">
                    @foreach(['cms_admin', 'support_admin', 'client_queries','approve_queries'] as $permissionName)
                        <input type="checkbox" style="border: 2px solid #1b3a62" class="form-check-input" wire:model="permission" value="{{$permissionName}}" >
                        <label class="form-check-label" style="color: #1B3A62">{{ ucwords(str_replace('_', ' ', $permissionName)) }}</label>
                        <br>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <h5 class="text-bold">Please assign permissions to Sub Admin for HAi Admin Dashboard:</h5>
                <div class="col-sm-4 col-6 w-50">
                    @foreach(['persona', 'brains'] as $permissionName)
                        <input type="checkbox" style="border: 2px solid #1b3a62" class="form-check-input" wire:model="permission" value="{{$permissionName}}">
                        <label class="form-check-label" style="color: #1B3A62">@if($permissionName === 'hai_admin')
                                HAi Admin
                            @else
                                {{ ucwords(str_replace('_', ' ', $permissionName)) }}
                            @endif</label>
                        <br>
                    @endforeach
                </div>
                <div class="col-sm-4 col-6 w-50" style="padding-left: 27px">
                    @foreach(['knowledge', 'advance'] as $permissionName)
                        <input type="checkbox" style="border: 2px solid #1b3a62" class="form-check-input" wire:model="permission" value="{{$permissionName}}" >
                        <label class="form-check-label" style="color: #1B3A62">{{ ucwords(str_replace('_', ' ', $permissionName)) }}</label>
                        <br>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <h5 class="text-bold">Please assign permissions to Sub Admin for B2B Admin Dashboard:</h5>
                <div class="col-sm-4 col-6 w-50">
                    @foreach(['organizations','role_template_manage', 'invites'] as $permissionName)
                        <input type="checkbox" style="border: 2px solid #1b3a62" class="form-check-input" wire:model="permission" value="{{$permissionName}}">
                        <label class="form-check-label" style="color: #1B3A62">@if($permissionName === 'hai_admin')
                                HAi Admin
                            @else
                                {{ ucwords(str_replace('_', ' ', $permissionName)) }}
                            @endif</label>
                        <br>
                    @endforeach
                </div>
                <div class="col-sm-4 col-6 w-50" style="padding-left: 27px">
                    @foreach(['pricing_plan', 'b2b_support_admin', 'coupons'] as $permissionName)
                        <input type="checkbox" style="border: 2px solid #1b3a62" class="form-check-input" wire:model="permission" value="{{$permissionName}}" >
                        <label class="form-check-label" style="color: #1B3A62">{{ ucwords(str_replace('_', ' ', $permissionName)) }}</label>
                        <br>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Update Permission</button>
        </div>
    </form>
</div>


