@push('css')
    <style>

        .swal2-input.custom-input {
            width: 100% !important;
            max-width: 400px; /* prevents it from being too wide on large screens */
            box-sizing: border-box;
        }

        .custom-confirm-btn {
            background-color: #F2661C !important;
            color: white !important;
            border: none;
        }

        .custom-swal-btn {
            background-color: #F2661C !important;
            color: white !important;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
@endpush
<div>

    <div class="card-header table-header-text">
        <h5 class="mb-0 mt-2 text-color-blue">B2B Client's</h5>
        @if(count($selectedItems) > 0)
        <div class=" d-flex justify-content-end ms-md-4 pe-md-4">
        <button type="button" onclick="bulkDeleted()"  class="btn btn-danger">Delete B2B Clients Permanently</button>
        </div>
        @endif
    </div>

    <div class="table-header-text">
        <div class="d-flex mt-4">
            <div class="input-group ms-md-4 pe-md-4">
                <input type="text" name="name" wire:model.debounce="name"
                       class="form-control table-orange-color search-bar" placeholder="Search Name">
            </div>
            <div class="input-group ms-md-4 pe-md-4">
                <input type="email" name="email" wire:model.debounce="email"
                       class="form-control table-orange-color search-bar" placeholder="Search Email">
            </div>

        </div>
    </div>


    <div class="table-responsive w-100 pt-4 table-orange-color">
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="text-color-blue">
                <th class="text-center">Name</th>
                <th class="text-center">Email</th>
                <th class="text-center">Gender</th>
                <th class="text-center">Members</th>
                <th class="text-center">Candidates</th>
                <th class="text-center">Bulk Delete</th>
                <th class="text-center">Change Password</th>
                <th class="text-center">Delete</th>

            </tr>
            </thead>
            <tbody>



            @foreach($users as $user)

                <tr class="text-color-blue">
                    <td class="text-sm font-weight-normal text-center">{{$user['first_name'].' '.$user['last_name'] }} </td>
                    <td class="text-sm font-weight-normal text-center">{{$user['email']}}</td>
                    <td class="text-sm font-weight-normal text-center">{{$user['gender']==0 ? 'Male':'FeMale'}}</td>
                    <td class="text-sm font-weight-normal text-center">
                       
                        <a href="{{ route('b2b_organizations_users', ['id' => $user['id'], 'prefer' => 1]) }}"
                            style="border: 1px solid #f2661c; color: white; background-color: #f2661c;"
                            class="btn btn-sm mb-0">{{$user['member_count']}}</a>
                    </td>
                    <td class="text-sm font-weight-normal text-center">
                       

                         <a href="{{ route('b2b_organizations_users', ['id' => $user['id'], 'prefer' => 2]) }}"
                            style="border: 1px solid #f2661c; color: white; background-color: #f2661c;"
                            class="btn btn-sm mb-0">
                            {{ $user['candidate_count'] }}
                        </a>
                        
                    </td>

                    <td class="text-center">
                        <input type="checkbox" wire:model="selectedItems" value="{{ $user->id }}"
                            style="width: 20px; height: 20px; cursor: pointer; accent-color: #f2661c; border-radius: 50%;">
                    </td>
                    <td class="text-sm font-weight-normal text-center">
                        <a onclick="resetPassword({{ $user['id'] ?? null }}, '{{ $user['first_name'] ?? null }}')"
                           style="border: 1px solid #f2661c; color: white; background-color: red;"
                           class="btn btn-sm mb-0">
                            Reset Password
                        </a>
                    </td>
                    <td class="text-sm font-weight-normal text-center">
                        <a onclick="deleteProfile({{ $user['id'] ?? null }})"
                           style="border: 1px solid #f2661c; color: white; background-color: red;"
                           class="btn btn-sm mb-0">
                            Delete
                        </a>
                    </td>


                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $users->links('pagination.table-pagination') }}
    </div>
</div>

@push('js')
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>
    <script>


function resetPassword(id, name) {
    Swal.fire({
        title: `<span style="color:white;">Reset Password</span>`,
        html: `
            <div style="position: relative; display: flex; justify-content: center;">
                <input type="password" id="newPassword" class="swal2-input custom-input" placeholder="Enter new password">
                <span id="togglePassword" style="position: absolute; right: 40px; top: 60%; transform: translateY(-50%); cursor: pointer; color: #999;">
                    <i class="fa fa-eye" id="eyeIcon"></i>
                </span>
            </div>
        `,
        background: '#1c365e',
        color: 'white',
        showCancelButton: true,
        confirmButtonText: 'Reset',
        cancelButtonText: 'Cancel',
        customClass: {
            confirmButton: 'btn custom-confirm-btn m-2',
            cancelButton: 'btn bg-gradient-secondary m-2',
        },
        buttonsStyling: false,
        didOpen: () => {
            const toggle = Swal.getPopup().querySelector('#togglePassword');
            const input = Swal.getPopup().querySelector('#newPassword');
            const icon = Swal.getPopup().querySelector('#eyeIcon');

            toggle.addEventListener('click', () => {
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                icon.className = type === 'password' ? 'fa fa-eye' : 'fa fa-eye-slash';
            });
        },
        preConfirm: () => {
            const password = Swal.getPopup().querySelector('#newPassword').value;
            if (!password) {
                Swal.showValidationMessage(`Please enter a password`);
            }
            return { password: password };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const password = result.value.password;
            window.livewire.emit('resetPassword', id, password);
        }
    });
}


    </script>

    <script>
        window.addEventListener('swal:error', event => {
            Swal.fire({
                icon: 'error',
                text: event.detail.message,
                background: '#1c365e',
                color: '#f6ba81',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'custom-swal-btn'
                },
                buttonsStyling: false
            });
        });

        window.addEventListener('swal:success', event => {
            Swal.fire({
                icon: 'success',
                text: event.detail.message,
                background: '#1c365e',
                color: '#f6ba81',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'custom-swal-btn'
                },
                buttonsStyling: false
            });
        });
    </script>

    <script>
        function deleteProfile(businessId) {

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
    html: "<span style='color: white;'>Want to delete  Profile</span>",
    showCancelButton: true,
    confirmButtonText: 'Delete',
}).then((result) => {
    if (result.isConfirmed) {
        window.livewire.emit('deleteB2BAdminProfile', businessId)
    }
})
}



function bulkDeleted(){
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
            html: "<span style='color: white;'><strong>Permanently delete the B2B Admin account and all related data.</strong></span>",
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if(result.isConfirmed){
                window.livewire.emit('bulkDelete')
            }
        })
    }

    </script>


@endpush
