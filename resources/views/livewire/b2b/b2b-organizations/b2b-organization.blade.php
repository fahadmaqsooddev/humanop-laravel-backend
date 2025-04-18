@push('css')
<style>
    .swal2-styled.swal2-confirm {
        background-image: none !important;
        background-color: #f2661c !important;
        padding: 0.75rem 1.5rem;
        font-size: 0.75rem;
        border-radius: 0.5rem;
        color: white !important; /* optional: ensure text is readable */
    }
</style>

@endpush
<div>
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
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Members</th>
                <th>Candidates</th>
                <th>Change Password</th>
               
            </tr>
            </thead>
            <tbody>
                
                
                
            @foreach($users as $user)
            
                <tr class="text-color-blue">
                    <td class="text-sm font-weight-normal ">{{$user['first_name'].' '.$user['last_name'] }} </td>
                    <td class="text-sm font-weight-normal ">{{$user['email']}}</td> 
                    <td class="text-sm font-weight-normal text-center">{{$user['gender']==0 ? 'Male':'FeMale'}}</td> 
                    <td class="text-sm font-weight-normal text-center">
                        {{$user['member_count']}}
                    </td>
                    <td class="text-sm font-weight-normal text-center">
                        {{$user['candidate_count']}}
                    </td>
                    <td class="text-sm font-weight-normal text-center">
                        <a onclick="resetPassword({{ $user['id'] ?? null }}, '{{ $user['first_name'] ?? null }}')"
                           style="border: 1px solid #f2661c; color: white; background-color: red;"
                           class="btn btn-sm mb-0">
                            Reset Password
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
        function updateUserHaiChatStatus(id, name, checkbox, e) {
            e.preventDefault();

            // Store the current state of the checkbox
            const isChecked = checkbox.checked;

            // Reset checkbox to its original state temporarily
            checkbox.checked = !isChecked;

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-primary m-2',
                    cancelButton: 'btn bg-gradient-secondary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            });

            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Want to change HAI Chat Visibility for " + name + ".</span>",
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, re-check the checkbox
                    checkbox.checked = isChecked;
                    // Trigger Livewire event
                    window.livewire.emit('updateHaiChatVisibility', id);
                } else {
                    // If not confirmed, reset the checkbox to its original state
                    checkbox.checked = !isChecked;
                }
            });
        }
        function updateUserEmailVerifiedStatus(id, name, checkbox, e) {
            e.preventDefault();

            // Store the current state of the checkbox
            const isChecked = checkbox.checked;
            const status = checkbox.id;



            // Reset checkbox to its original state temporarily
            checkbox.checked = !isChecked;

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-primary m-2',
                    cancelButton: 'btn bg-gradient-secondary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            });
            let title = '';
            let html = '';
            if (status == 1) {
        title = '<span style="color: white;">Are you sure?</span>';
        html = "<span style='color: white;'>This email is already verified.</span>";
    } else {
        title = '<span style="color: white;">Are you sure?</span>';
        html = "<span style='color: white;'>Want to verify the email of  " + name + "?</span>";
    }
            swalWithBootstrapButtons.fire({
                title: title,
                html: html,
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((result) => {
                if (result.isConfirmed) {

                    if(status!=1){
                        checkbox.checked = isChecked;

                    window.livewire.emit('updateEmailVerified', id);
                    }

                } else {

                    checkbox.checked = !isChecked;
                }
            });
        }


        function adminLoggedInToUserAccount(id, firstName, lastName, identify) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-primary m-2',
                    cancelButton: 'btn bg-gradient-secondary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Want to log in as " + firstName + " " + lastName + "</span>",
                showCancelButton: true,
                confirmButtonText: 'Log in',
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.setItem('is_admin', true);
                    window.livewire.emit('logInAdminAsUser', id, identify)
                }
            })
        }

        function changeUserToPractitioner(id, name, checkbox, e) {
            e.preventDefault();

            // Store the current state of the checkbox
            const isChecked = checkbox.checked;

            // Reset checkbox to its original state temporarily
            checkbox.checked = !isChecked;

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-primary m-2',
                    cancelButton: 'btn bg-gradient-secondary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            });

            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Want to Change Practitioner Status for " + name + ".</span>",
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, re-check the checkbox
                    checkbox.checked = isChecked;
                    // Trigger Livewire event
                    window.livewire.emit('makePractitioner', id);
                } else {
                    // If not confirmed, reset the checkbox to its original state
                    checkbox.checked = !isChecked;
                }
            });
        }

        // function deleteClientProfile(id, name) {

        //     const swalWithBootstrapButtons = Swal.mixin({
        //         customClass: {
        //             confirmButton: 'btn bg-gradient-danger m-2',
        //             cancelButton: 'btn bg-gradient-secondary m-2',
        //         },
        //         buttonsStyling: false,
        //         background: '#3442b4',
        //     })
        //     swalWithBootstrapButtons.fire({
        //         title: '<span style="color: white;">Are you sure?</span>',
        //         html: "<span style='color: white;'>Want to delete " + name + " Profile</span>",
        //         showCancelButton: true,
        //         confirmButtonText: 'Delete',
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             window.livewire.emit('deleteClientProfile', id)
        //         }
        //     })
        // }


        function resetPassword(id, name) {
    Swal.fire({
        title: `<span style="color:white;">Reset Password</span>`,
        html: `
            <input type="password" id="newPassword" class="swal2-input" placeholder="Enter new password">`,
        background: '#3442b4',
        color: 'white',
        showCancelButton: true,
        confirmButtonText: 'Reset',
        cancelButtonText: 'Cancel',
        customClass: {
            confirmButton: 'btn bg-gradient-danger m-2',
            cancelButton: 'btn bg-gradient-secondary m-2',
        },
        buttonsStyling: false,
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
            // Emit to Livewire
            window.livewire.emit('resetPassword', id, password);
        }
    });
}


        function deleteBulkClient(){
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
                html: "<span style='color: white;'>Want to delete Clients </span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('bulkDelete')
                }
            })
        }

        function displayChatBotPublishMessage(event){
            event.preventDefault();

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-danger m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                // title: '<span style="color: white;">Publish Chat Bot first to enable Hai chat.</span>',
                html: "<h4 style='color: white;'>Publish Chat Bot first to enable Hai chat.</h4>",
                // showCancelButton: true,
                confirmButtonText: 'Close',
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.checked = false;
                }
            })

        }


        function changeUserMemberShip(e, user_id) {

            window.Livewire.emit('changeUserMemberShip', e.options.selectedIndex, user_id);
        }

        
    </script>
    <script>
        window.addEventListener('swal:error', event => {
            Swal.fire({
                icon: 'error',
                text: event.detail.message,
                background: '#1c365e',
                color: '#f6ba81',
            });
        });
    
        window.addEventListener('swal:success', event => {
            Swal.fire({
                icon: 'success',
                text: event.detail.message,
                background: '#1c365e',
                color: '#f6ba81',
            });
        });
    </script>
    
@endpush
