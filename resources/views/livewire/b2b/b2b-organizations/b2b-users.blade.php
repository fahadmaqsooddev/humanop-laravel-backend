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
        <h5 class="mb-0"> All {{$prefer==1 ? 'Members':'Candidates'}}</h5>
       
    </div>
    {{-- <div class="table-header-text">
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
    </div> --}}


    <div class="table-responsive w-100 pt-4 table-orange-color">
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="text-color-blue">
                <th class="text-center">Name</th>
                <th class="text-center">Email</th>
                <th class="text-center">Gender</th>
                <th class="text-center">Role</th>
                <th class="text-center">Delete</th>
                <th class="text-center">Future Consideration</th>

            </tr>
            </thead>
            <tbody>



            @foreach($data as $user)

                <tr class="text-color-blue">
                    <td class="text-sm font-weight-normal text-center">{{$user['users']['first_name'].' '.$user['users']['last_name'] }} </td>
                    <td class="text-sm font-weight-normal text-center">{{$user['users']['email']}}</td>
                    <td class="text-sm font-weight-normal text-center">{{$user['users']['gender']==0 ? 'Male':'FeMale'}}</td>
                    <td class="text-sm font-weight-normal text-center">{{$user['role']==0 ? 'Member':'Candidate'}}</td>
                    
                    <td class="text-sm font-weight-normal text-center">
                        <a onclick="deleteClientProfile({{$user['business_id'] ?? null}}, '{{$user['candidate_id'] ?? null}}')"
                           style="border: 1px solid #f2661c; color: white; background-color: red;"
                           class="btn btn-sm mb-0">
                            Delete {{$user['role']==0 ? 'Member':'Candidate'}}
                        </a>
                    </td>
                    <td class="text-sm font-weight-normal text-center">
                        <a onclick="FutureConsiderationClientProfile({{$user['business_id'] ?? null}}, '{{$user['candidate_id'] ?? null}}')"
                           style="border: 1px solid #f2661c; color: white; background-color: red;"
                           class="btn btn-sm mb-0">
                            Future Consideration {{$user['role']==0 ? 'Member':'Candidate'}}
                        </a>
                    </td>


                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $data->links('pagination.table-pagination') }}
    </div>
</div>

@push('js')
   
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    


    <script>
        
function deleteClientProfile(businessId, candidateId) {

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
        window.livewire.emit('deleteClientProfile', businessId,candidateId)
    }
})
}


    </script>
    <script>
        
function FutureConsiderationClientProfile(businessId, candidateId) {

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
    html: "<span style='color: white;'>Want to Future Consideration Profile</span>",
    showCancelButton: true,
    confirmButtonText: 'Yes',
}).then((result) => {
    if (result.isConfirmed) {
        window.livewire.emit('FutureConsiderationClientProfile', businessId,candidateId)
    }
})
}


    </script>


@endpush
