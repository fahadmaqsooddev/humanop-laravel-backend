<div class="table-responsive table-orange-color">
    <table class="table table-flush" id="datatable-search">
        <thead class="table-text-color">
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
            <tr class="table-text-color">
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
                <td class="text-sm font-weight-normal">{{ ($admin->gender != null ? $admin->gender === '0' ? 'Male' : "Female" : '-') }}</td>
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
                    <a onclick="deleteSubAdmin({{ $admin->id }})" class=" btn-sm float-end mt-2 mb-0"
                       style="background:#ff0000;color:white;font-weight:bolder;cursor:pointer;">Delete</a>
                    <a data-bs-toggle="modal" data-bs-target="#subadmindetail{{ $admin->id }}"
                       class=" btn-sm float-end mt-2 mb-0"
                       style="background:#1B3A62;color:white;font-weight:bolder;margin-right:1rem;">View</a>
                </td>

            </tr>
            <div wire:ignore.self class="modal fade" id="subadmindetail{{ $admin->id }}" tabindex="-1" role="dialog"
                 aria-labelledby="subadmindetail{{ $admin->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body" style=" border-radius: 9px">
                            <label class="form-label fs-4 text-white">Permissions</label>
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close">
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
<script src="../../assets/js/plugins/sweetalert.min.js"></script>
<script>
    function deleteSubAdmin(id) {

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn bg-gradient-danger m-2',
                cancelButton: 'btn bg-gradient-secondary m-2',
            },
            buttonsStyling: false,
            background: '#3442b4',
        })
        swalWithBootstrapButtons.fire({
            title: '<span style="color: white;">Are you sure?</span>',
            html: "<span style='color: white;'>Want to delete Sub Admin</span>",
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                window.livewire.emit('deleteSubAdmin', id)
            }
        })
    }
</script>
