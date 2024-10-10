<div>
    <div class="table-header-text">
        <div class="d-flex mt-4">
            <div class="input-group ms-md-4 pe-md-4">
        <span style="background-color: #0f1534;" class="input-group-text text-body"><i class="fas fa-search"
                                                                                       aria-hidden="true"></i></span>
                <input type="text" style="background-color: #0f1534;" name="name" wire:model.debounce="name"
                       class="form-control text-white" placeholder="Search Name">
            </div>
            <div class="input-group ms-md-4 pe-md-4">
        <span style="background-color: #0f1534;" class="input-group-text text-body"><i class="fas fa-search"
                                                                                       aria-hidden="true"></i></span>
                <input type="email" style="background-color: #0f1534;" name="email" wire:model.debounce="email"
                       class="form-control text-white" placeholder="Search Email">
            </div>
            <div class="input-group ms-md-4 pe-md-4">
                <select style="background-color: #0f1535" class="form-control text-white" name="age"
                        wire:model.debounce="age">
                    <option value="">Select Age</option>
                    <option value="5-6">5-6</option>
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
    <div class="table-responsive w-100 pt-4 table-orange-color">
        <table class="table table-flush">
            <thead class="thead-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                @if(Auth::user()->hasRole('super admin'))
                    <th>HAI Chat</th>
                @endif
                @if(Auth::user()->hasRole('super admin') || Auth::user()->hasRole('sub admin'))
                    <th>Membership</th>
                    <th>Practitioner</th>
                    <th>Login Client</th>
                    <th>Delete Client</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="text-sm font-weight-normal">{{$user['first_name'].' '.$user['last_name'] }} </td>
                    <td class="text-sm font-weight-normal">{{$user['email']}}</td>
                    <td class="text-sm font-weight-normal">{{$user['gender'] === '0' ? 'Male' : 'Female'}}</td>
                    @if(Auth::user()->hasRole('super admin'))

                        <td class="text-sm font-weight-normal">
                            <div class="form-check form-switch mb-0 d-flex justify-content-center">
                                @php
                                    if($user->hai_chat == 1)
                                        $status = true;
                                    else
                                        $status = false;
                                @endphp
                                <input class="form-check-input"
                                       onchange="updateUserHaiChatStatus({{$user['id']}}, '{{$user['first_name']}}', this , event)"
                                       name="status"
                                       type="checkbox"
                                       @checked($status)>
                            </div>
                        </td>

                    @endif
                    @if(Auth::user()->hasRole('super admin') || Auth::user()->hasRole('sub admin'))
                        <td class="text-sm font-weight-normal">
                            <select class="form-control" onchange="changeUserMemberShip(this, {{$user['id']}})"
                                    style="background-color: #0F1535; color: white; border-radius: 12px;">
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
                        <td class="text-sm font-weight-normal">
                            <a onclick="adminLoggedInToUserAccount({{$user['id'] ?? null}}, '{{$user['first_name'] ?? null}}')"
                               style="border: 1px solid #f2661c; color: #f2661c; background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%) border-box;"
                               class="btn btn-sm float-end mt-2 mb-0">
                                Login
                            </a>
                        </td>
                        <td class="text-sm font-weight-normal">
                            <a onclick="deleteClientProfile({{$user['id'] ?? null}}, '{{$user['first_name'] ?? null}}')"
                               style="border: 1px solid #f2661c; color: white; background-color: red;"
                               class="btn btn-sm float-end mt-2 mb-0">
                                Delete
                            </a>
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


        function adminLoggedInToUserAccount(id, name) {

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
                html: "<span style='color: white;'>Want to log in as " + name + "</span>",
                showCancelButton: true,
                confirmButtonText: 'Log in',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('logInAdminAsUser', id)
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


        function changeUserMemberShip(e, user_id) {

            window.Livewire.emit('changeUserMemberShip', e.options.selectedIndex, user_id);
        }
    </script>
@endpush
