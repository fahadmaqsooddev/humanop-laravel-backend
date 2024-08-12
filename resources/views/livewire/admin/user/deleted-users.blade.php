<div>

    <div class="card-header">
        <h5 class="mb-0 text-white">Deleted Client's</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Email</th>
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
                    <td>
                        <button wire:click="restoreUser({{$user->id}})" class="btn updateBtn" title="restore">
                            <i class="fa-solid fa-rotate-right"></i>
                        </button>

                        <button wire:click="deleteUserPermanently({{$user->id}})" class="btn btn-danger" title="delete permanently">
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

<script>
    const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
        searchable: true,
        fixedHeight: true
    });
</script>
