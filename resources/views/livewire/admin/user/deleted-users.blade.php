<div>

    <div class="card-header table-header-text">
        <h5 class="mb-0 mt-2 text-color-blue">Deleted Client's</h5>
        @if(count($selectedItems) > 0)
        <div class=" d-flex justify-content-end ms-md-4 pe-md-4">
        <button type="button" onclick="bulkDeleted()"  class="btn btn-danger">Delete Clients Permanently</button>
        </div>
        @endif

   
    

    
    </div>

  
    <div class="table-responsive table-orange-color">
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="text-color-blue">
                <th>#</th>
                <th>Username</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Bulk Delete</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @if(!isset($users[0]))
                <tr class="text-color-blue">
                    <td>No client found...</td>
                </tr>
            @endif
            @foreach($users as $key => $user)
                <tr class="text-color-blue">
                    <td class="text-md font-weight-normal">{{$key + 1}}</td>
                    <td class="text-md font-weight-normal">{{$user['first_name'] . ' ' . $user['last_name']}}</td>
                    <td class="text-md font-weight-normal">{{$user['email']}}</td>
                    <td class="text-md font-weight-normal">{{$user['is_admin'] == 4 ? "Practitioner" : "Client"}}</td>
                    <td class="text-center">
                        <input type="checkbox" wire:model="selectedItems" value="{{ $user->id }}"
                            style="width: 20px; height: 20px; cursor: pointer; accent-color: #f2661c; border-radius: 50%;">
                    </td>
                   
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

        {{ $users->links('pagination.table-pagination') }}

    </div>

</div>

{{--<script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>--}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../assets/js/plugins/sweetalert.min.js"></script>

<script>
    // const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
    //     searchable: true,
    //     fixedHeight: true
    // });

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
            html: "<span style='color: white;'><strong>Permanently delete the user account and all related data.</strong></span>",
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if(result.isConfirmed){
                window.livewire.emit('deleteUser', [user_id])
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
            html: "<span style='color: white;'><strong>Permanently delete the Clients account and all related data.</strong></span>",
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if(result.isConfirmed){
                window.livewire.emit('bulkDelete')
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
