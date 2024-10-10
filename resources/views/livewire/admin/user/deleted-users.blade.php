<div>

    <div class="card-header table-header-text">
        <h5 class="mb-0 text-white">Deleted Client's</h5>
    </div>
    <div class="table-responsive table-orange-color">
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @if(!isset($users[0]))
                <tr>
                    <td>No client found...</td>
                </tr>
            @endif
            @foreach($users as $key => $user)
                <tr>
                    <td class="text-sm font-weight-normal">{{$key + 1}}</td>
                    <td class="text-sm font-weight-normal">{{$user['first_name'] . ' ' . $user['last_name']}}</td>
                    <td class="text-sm font-weight-normal">{{$user['email']}}</td>
                    <td class="text-sm font-weight-normal">{{$user['is_admin'] == 4 ? "Practitioner" : "Client"}}</td>
                    <td>
                        <button onclick="confirmBoxForRestoreUser({{$user->id}})" class="btn updateBtn" title="restore">
                            <i class="fa-solid fa-rotate-right"></i>
                        </button>

                        <button class="btn btn-danger" title="delete permanently"
                        onclick="confirmBoxForPermanentDelete({{$user->id}})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>

</div>

<script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../assets/js/plugins/sweetalert.min.js"></script>

<script>
    const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
        searchable: true,
        fixedHeight: true
    });

    function confirmBoxForPermanentDelete(user_id){

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
            html: "<span style='color: white;'>Want to delete user permanently!</span>",
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if(result.isConfirmed){
                window.livewire.emit('deleteUser', [user_id])
            }
        })
    }

    function confirmBoxForRestoreUser(user_id){

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn bg-gradient-primary m-2',
                cancelButton:  'btn bg-gradient-danger m-2',
            },
            buttonsStyling: false,
            background : '#3442b4',
        })
        swalWithBootstrapButtons.fire({
            title: '<span style="color: white;">Are you sure?</span>',
            html: "<span style='color: white;'>Want to restore user!</span>",
            showCancelButton: true,
            confirmButtonText: 'Restore',
        }).then((result) => {
            if(result.isConfirmed){
                window.livewire.emit('restoreUser', [user_id])
            }
        })
    }

</script>
