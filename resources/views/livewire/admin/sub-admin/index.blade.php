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
                            <label class="form-label fs-4" style="color: #1B3A62">Permissions</label>
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

    <div wire:ignore.self class="modal fade" id="addSubAdmin" tabindex="-1"
         role="dialog"
         aria-labelledby="addSubAdmin" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Add Sub Admin</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="submitForm">
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label text-color-dark">First Name</label>
                                        <div class="input-group">
                                            <input id="firstName"
                                                   wire:model.defer="sub_admin.first_name"
                                                   class="form-control input-form-style" type="text" placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-color-dark">Last Name</label>
                                        <div class="input-group">
                                            <input id="lastName" wire:model.defer="sub_admin.last_name"
                                                   class="form-control input-form-style" type="text" placeholder="Last Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label mt-4 text-color-dark">Email</label>
                                        <div class="input-group">
                                            <input wire:model.defer="sub_admin.email"
                                                   class="form-control input-form-style"  placeholder="email">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label mt-4 text-color-dark">Phone Number</label>
                                        <div class="input-group">
                                            <input wire:model.defer="sub_admin.phone"
                                                   class="form-control input-form-style" type="text" placeholder="Phone #">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label mt-4 text-color-dark">Password</label>
                                        <div class="input-group">
                                            <input wire:model="sub_admin.password" class="form-control input-form-style" placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-6 w-50">
                                        <label class="form-label mt-4 text-color-dark">I'm</label>
                                        <select class="form-control input-form-style text-color-dark"
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
                                            <select class="justify-content-center form-control input-form-style m-1 text-color-dark"
                                                    wire:model="month">
                                                <option value="">Month</option>
                                                @foreach($months as $key => $month)
                                                    <option value="{{$key + 1}}">{{$month}}</option>
                                                @endforeach
                                            </select>
                                            <select class="justify-content-center form-control input-form-style m-1 text-color-dark"
                                                    wire:model="day">
                                                <option value="">Day</option>
                                                @for($i = 1; $i <= 31; $i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                            <select class="justify-content-center form-control input-form-style m-1 text-color-dark"
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
                                    <h5 class="text-bold mt-4 mb-2">
                                        Please assign permissions to Sub Admin for B2C Admin Dashboard:
                                    </h5>
                                    <div class="row">
                                        <div class="col-sm-4 col-6 w-50">
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.user_management">
                                            <label class="form-check-label text-color-dark">User Management</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.assessment_management">
                                            <label class="form-check-label text-color-dark">Assessment Management</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.technology_management">
                                            <label class="form-check-label text-color-dark">Technology Management</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.team_management">
                                            <label class="form-check-label text-color-dark">Team Management</label>

                                        </div>
                                        <div class="col-sm-4 col-6 w-50" style="padding-left: 27px">
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.cms_admin">
                                            <label class="form-check-label text-color-dark">CMS Admin</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.support_admin">
                                            <label class="form-check-label text-color-dark">Support Admin</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.client_queries">
                                            <label class="form-check-label text-color-dark">Client Queries</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.approve_queries">
                                            <label class="form-check-label text-color-dark">Approve Queries</label>
                                            <br>
                                        </div>
                                    </div>
                                    <h5 class="text-bold mt-4 mb-2">
                                        Please assign permissions to Sub Admin for HAi Admin Dashboard:
                                    </h5>
                                    <div class="row">
                                        <div class="col-sm-4 col-6 w-50">
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.persona">
                                            <label class="form-check-label text-color-dark">Persona</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.brains">
                                            <label class="form-check-label text-color-dark">Brains</label>

                                        </div>
                                        <div class="col-sm-4 col-6 w-50" style="padding-left: 27px">
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.advance">
                                            <label class="form-check-label text-color-dark">Advance</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.knowledge">
                                            <label class="form-check-label text-color-dark">Knowledge</label>
                                        </div>
                                        <div class="col-sm-4 col-6 w-50">
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.suggestions">
                                            <label class="form-check-label text-color-dark">Suggestions</label>
                                        </div>
                                    </div>
                                    <h5 class="text-bold mt-4 mb-2">
                                        Please assign permissions to Sub Admin for B2B Admin Dashboard:
                                    </h5>
                                    <div class="row">
                                        <div class="col-sm-4 col-6 w-50">
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.organizations">
                                            <label class="form-check-label text-color-dark">Organizations</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.recycle_organizations">
                                            <label class="form-check-label text-color-dark">Recycle Organizations</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.support">
                                            <label class="form-check-label text-color-dark">B2B Support</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.compatibility_matrix">
                                            <label class="form-check-label text-color-dark">Compatibility Matrix</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.role_template_manage">
                                            <label class="form-check-label text-color-dark">Role Template Manage</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.faq">
                                            <label class="form-check-label text-color-dark">FAQ</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.invites">
                                            <label class="form-check-label text-color-dark">Invites</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.intro_videos">
                                            <label class="form-check-label text-color-dark">Intro Videos</label>
                                            <br>
                                        </div>
                                        <div class="col-sm-4 col-6 w-50" style="padding-left: 27px">

                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.intro_informations">
                                            <label class="form-check-label text-color-dark">Intro Informations</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.business_strategies">
                                            <label class="form-check-label text-color-dark">Business Strategies</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.user_intentions">
                                            <label class="form-check-label text-color-dark">User Intentions</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.email_templates">
                                            <label class="form-check-label text-color-dark">Email Templates</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.activity_logs">
                                            <label class="form-check-label text-color-dark">Activity Logs</label>
                                            <br>
                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.pricing_plan">
                                            <label class="form-check-label text-color-dark">Pricing Plan</label>
                                            <br>

                                            <input type="checkbox"
                                                   class="form-check-input" style="border: 2px solid #1b3a62"
                                                   wire:model.defer="permission.redemption_code">
                                            <label class="form-check-label text-color-dark">Redemption Code</label>
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
                </div>
            </div>
        </div>
    </div>

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
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('refreshPage', () => {
            window.location.reload();
        });
    });
</script>
