<div class="table-responsive">
    <table class="table table-flush" id="datatable-search">
        <thead class="thead-light">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Status</th>
            <th>Date</th>
            <th class="text-center">Permissions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($admins as $admin)
            @php
                $permissions = $admin->getAllPermissions();
                $permissionNames = $permissions->pluck('name')->toArray();
            @endphp
            <tr>
                @if(session('success'.$admin->id))
                    <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{session('success'.$admin->id) }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                @elseif(session('error'.$admin->id))
                    <div class="m-3  alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                                        <span class="alert-text text-white">
                                            {{session('error'.$admin->id) }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                @endif
                <td class="text-sm font-weight-normal">{{ $admin->first_name . ' ' . $admin->last_name }}</td>
                <td class="text-sm font-weight-normal">{{ $admin->email }}</td>
                <td class="text-sm font-weight-normal">{{ ($admin->gender == 2 ? 'Both' : ($admin->gender == 1 ? 'Female' : ($admin->gender === '0' ? 'Male' : ''))) }}</td>
                <td class="font-weight-normal">
                    <div class="form-check form-switch mb-0">
                        @php
                            if($admin->status == 1)
                                $status = true;
                            else
                                $status = false;
                        @endphp
                        <input class="form-check-input"
                               wire:model="statuses.{{ $admin->id }}"
                               wire:change="updateStatus({{ $admin->id }})"
                               name="status"
                               type="checkbox"
                               id="flexSwitchCheckDefault{{ $admin->id }}"
                               @checked($status) >
                    </div>
                </td>
                <td class="text-sm font-weight-normal">{{ \Carbon\Carbon::parse($admin->signup_date)->format('Y/m/d') }}</td>
                <td class="text-sm font-weight-normal">
                    <a data-bs-toggle="modal" data-bs-target="#subadmindetail{{ $admin->id }}" style="background-color: #f2661c; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a>
                </td>
            </tr>
            <div wire:ignore.self class="modal fade" id="subadmindetail{{ $admin->id }}" tabindex="-1" role="dialog" aria-labelledby="subadmindetail{{ $admin->id }}" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                            <label class="form-label fs-4 text-white">Permissions</label>
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            @livewire('admin.sub-admin.permission',['permission' => $permissionNames,'adminId' => $admin->id], key($admin->id))
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </tbody>
    </table>
</div>
