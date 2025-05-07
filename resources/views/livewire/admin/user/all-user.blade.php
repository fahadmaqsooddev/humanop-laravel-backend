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

            <div class="input-group ms-md-4 pe-md-4">
                <select class="form-control table-orange-color search-bar custom-text-dark" name="age"
                        wire:model.debounce="age">
                    <option value="">Select Age</option>
                    {{-- <option value="5-6">5-6</option> --}}
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
            </div>
        </div>
    </div>

    @if(Auth::user()->hasRole('super admin'))
        <button wire:click="hideHaiChatFromAllClients" class=" btn-sm float-end m-2 mb-0"
                style="background:#f2661c;color:white;font-weight:bolder;border:none;">Hai Chat Change Status
        </button>
        @if(count($selectedItems) > 0)
            <div class=" d-flex justify-content-end ms-md-4 pe-md-4 mt-2">
                <button type="button" onclick="deleteBulkClient()" class="btn-sm btn-danger"
                        style="font-weight:bolder;border:none;">Delete Clients
                </button>
            </div>
        @endif
    @endif

    <div class="table-responsive w-100 pt-4 table-orange-color">
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="text-color-blue">
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>HAI Chat</th>
                @if(Auth::user()->hasRole('super admin'))

                    <th>Email verified</th>

                @endif
                @if(Auth::user()->hasRole('super admin') || Auth::user()->hasRole('sub admin'))
                    <th>Membership</th>
                    <th>Practitioner</th>
                    {{-- <th>Login Client</th> --}}
                    <th>Bulk Delete</th>
                    <th>Delete Client</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="text-color-blue">
                    <td class="text-sm font-weight-normal text-center">{{$user['first_name'].' '.$user['last_name'] }} </td>
                    <td class="text-sm font-weight-normal">{{$user['email']}}</td>
                    <td class="text-sm font-weight-normal text-center">{{$user['gender'] != null ? $user['gender'] === '0' ? 'Male' : 'Female' : '-'}}</td>
                    <td class="text-sm font-weight-normal">
                        <div class="form-check form-switch mb-0 d-flex justify-content-center">
                            @php
                                if($user->hai_chat == 1)
                                    $status = true;
                                else
                                    $status = false;
                            @endphp

                            @if($is_chatBot_published || $status)

                                <input class="form-check-input"
                                       onchange="updateUserHaiChatStatus({{$user['id']}}, '{{$user['first_name']}}', this , event)"
                                       name="status"
                                       type="checkbox"
                                    @checked($status)>

                            @else

                                <input class="form-check-input"
                                       onchange="displayChatBotPublishMessage(event)"
                                       name="status"
                                       type="checkbox">

                            @endif
                        </div>
                    </td>

                    @if(Auth::user()->hasRole('super admin'))
                        <td class="text-sm font-weight-normal">
                            <div class="form-check form-switch mb-0 d-flex justify-content-center">
                                @php
                                    if(!empty($user['email_verified_at']))
                                        $status = true;
                                    else
                                        $status = false;
                                @endphp
                                <input class="form-check-input"
                                       onchange="updateUserEmailVerifiedStatus({{$user['id']}}, '{{$user['first_name']}}', this , event)"
                                       name="status" type="checkbox" id="{{$status}}"@checked($status)>
                            </div>
                        </td>
                    @endif
                    @if(Auth::user()->hasRole('super admin') || Auth::user()->hasRole('sub admin'))
                        <td class="text-sm font-weight-normal">
                            <select class="form-control table-orange-color table-text-color search-bar"
                                    onchange="changeUserMemberShip(this, {{$user['id']}})"
                                    style="background-color: #0F1535;border-radius: 12px;">
                                <option value="Freemium" {{$user['plan_name'] === "Freemium" ? 'selected' : ""}}>
                                    Freemium
                                </option>
                                <option value="Core" {{$user['plan_name'] === "Core" ? 'selected' : "" }}>Core</option>
                                <option value="Premium" {{$user['plan_name'] === "Premium" ? 'selected' : "" }}>Premium
                                </option>
                            </select>
                        </td>
                        <td class="text-sm font-weight-normal">
                            <div class="form-check form-switch mb-0 d-flex justify-content-center">
                                @php
                                    if($user->is_admin == 4)
                                        $practitionerStatus = true;
                                    else
                                        $practitionerStatus = false;
                                @endphp
                                <input class="form-check-input"
                                       onchange="changeUserToPractitioner({{$user['id']}}, '{{$user['first_name']}}', this , event)"
                                       name="practitioner"
                                       type="checkbox"
                                    @checked($practitionerStatus)>
                            </div>
                        </td>
                        <td class="text-center"><input type="checkbox" wire:model="selectedItems"
                                                       value="{{ $user->id }}"
                                                       style="width: 20px; height: 20px; cursor: pointer; accent-color: #f2661c; border-radius: 50%;">
                        </td>
                        <td class="text-sm font-weight-normal">
                            <a onclick="deleteClientProfile({{$user['id'] ?? null}}, '{{$user['first_name'] ?? null}}')"
                               style="border: 1px solid #f2661c; color: white; background-color: red;"
                               class="btn btn-sm float-end mt-2 mb-0">Delete</a>
                        </td>
                    @endif
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

                    if (status != 1) {
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

        function deleteClientProfile(id, name) {

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
                html: "<span style='color: white;'>Want to delete " + name + " Profile</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteClientProfile', id)
                }
            })
        }


        function deleteBulkClient() {
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

        function displayChatBotPublishMessage(event) {
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
@endpush
