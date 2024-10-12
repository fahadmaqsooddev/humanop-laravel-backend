<div>
    <div>
        <div class="d-flex mt-4">
            <div class="input-group ms-md-4 pe-md-4">
{{--        <span style="background-color: #0f1534;" class="input-group-text text-body"><i class="fas fa-search"--}}
{{--                                                                                       aria-hidden="true"></i></span>--}}
                <input type="text" name="name" wire:model.debounce="name"
                       class="form-control table-orange-color search-bar" placeholder="Search Name">
            </div>
            <div class="input-group ms-md-4 pe-md-4">
{{--        <span style="background-color: #0f1534;" class="input-group-text text-body"><i class="fas fa-search"--}}
{{--                                                                                       aria-hidden="true"></i></span>--}}
                <input type="email" name="email" wire:model.debounce="email"
                       class="form-control table-orange-color search-bar" placeholder="Search Email">
            </div>
            <div class="input-group ms-md-4 pe-md-4">
                <select class="form-control table-orange-color search-bar custom-text-dark" name="age"
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
            <tr class="text-color-blue">
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Membership</th>
                <th>URL</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="text-color-blue">
                    <td class="text-sm font-weight-normal">{{$user['first_name'].' '.$user['last_name'] }} </td>
                    <td class="text-sm font-weight-normal">{{$user['email']}}</td>
                    <td class="text-sm font-weight-normal">{{$user['gender'] === '0' ? 'Male' : 'Female'}}</td>
                    <td class="text-sm font-weight-normal">
                        <select class="form-control table-orange-color table-text-color search-bar" onchange="changeUserMemberShip(this, {{$user['id']}})" style="background-color: #0F1535; border-radius: 12px;">
                            <option value="Freemium" {{$user['plan_name'] === "Freemium" ? 'selected' : ""}}>Freemium</option>
                            <option value="Core" {{$user['plan_name'] === "Core" ? 'selected' : "" }}>Core</option>
                            <option value="Premium" {{$user['plan_name'] === "Premium" ? 'selected' : "" }}>Premium</option>
                        </select>
                    </td>
                    <td class="text-sm font-weight-normal">{{request()->getHttpHost(). '/' . $user['first_name'].'/'.$user['last_name']. '/login'}}</td>
                    <td class="text-sm font-weight-normal">
                        <a onclick="adminLoggedInToUserAccount({{$user['id'] ?? null}}, '{{$user['first_name'] ?? null}}')"
                           class="rainbow-border-user-nav-btn btn-sm float-end mt-2 mb-0">
                            Login
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
        function adminLoggedInToUserAccount(id, name){

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-primary m-2',
                    cancelButton:  'btn bg-gradient-secondary m-2',
                },
                buttonsStyling: false,
                background : '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Want to log in as "+name+"</span>",
                showCancelButton: true,
                confirmButtonText: 'Log in',
            }).then((result) => {
                if(result.isConfirmed){
                    window.livewire.emit('logInAdminAsUser', id)
                }
            })

        }

        function changeUserMemberShip(e, user_id){

            window.Livewire.emit('changeUserMemberShip', e.options.selectedIndex, user_id);
        }
    </script>
@endpush
