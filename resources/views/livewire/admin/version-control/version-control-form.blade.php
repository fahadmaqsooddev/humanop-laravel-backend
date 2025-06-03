<div class="row mt-4 container-fluid ms-2">


    <div class="col-lg-12 position-relative z-index-2">
        <div class="mb-4">
            <div class="card-body ">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="d-flex flex-column h-100">
                            <h2 class="font-weight-bolder custom-text-dark mb-0">Version Controls</h2>
                        </div>
                    </div>
                    <div class="col-lg-6 text-end">
                        <button class=" btn-sm mt-2 mb-0 me-auto" type="button" data-toggle="modal"
                                data-target=""
                                style="background:#1b3a62;color:white;font-weight:bolder;border:none;">
                                <a href="{{route('create-version')}}" style="color: white">

                                    Add version
                                </a>
                        </button>
                    </div>
                </div>
            </div>
        </div>




    </div>

    <div class="table-responsive table-orange-color " style="border-radius: 10px;">
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color">
                <th class="">Version Name</th>
                <th class="">Version Release</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
                @if(!isset($versions[0]))
                <tr class="text-color-blue">
                    <td>No Version found...</td>
                </tr>
            @endif
            @foreach($versions as $item)
                            <tr class="table-text-color">
                                <td class="text-md font-weight-normal ">{{$item['version'] ?? 'N/A'}} </td>
                                <td class="text-md font-weight-normal">{{$item['created_at']->format('F j, Y')}} </td>

                                <td class="text-center">
                                    <button style="background-color: red; color: white;margin-right: 5px;margin-bottom: 0px"
                                    onclick="confirmDeleteVersion('{{$item->id }}')" class="btn btn-sm mb-2">Delete
                                Version
                            </button>

                            <button style="background-color: #1b3a62; color: white;margin-bottom: 0px"
                            data-bs-toggle="modal"
                            wire:click=""
                            data-bs-target="#versionModel"  class="btn btn-sm mb-2 ">
                            <a href="{{ route('edit-version', ['id' => $item->id]) }}" style="color: white">
                                Edit Version
                            </a>

                            </button>
                                </td>
                            </tr>
                        @endforeach



            </tbody>
        </table>
    </div>


</div>

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>

    <script src="../../assets/js/plugins/sweetalert.min.js"></script>


    <script>
        function toggleCategoryBtn(id) {
            if ($('#category_edit_' + id).hasClass('d-flex')) {
                $('#category_edit_' + id).removeClass('d-flex justify-content-end').addClass('d-none');
            } else {
                $('#category_edit_' + id).removeClass('d-none').addClass('d-flex justify-content-end');
            }
        }




        function confirmDeleteVersion(category_id) {

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn bg-gradient-danger m-2',
        cancelButton: 'btn bg-gradient-primary m-2',
    },
    buttonsStyling: false,
    background: '#3442b4',
})
swalWithBootstrapButtons.fire({
    title: '<span style="color: white;">Are you sure?</span>',
    html: "<span style='color: white;'>Want to delete version  permanently!</span>",
    showCancelButton: true,
    confirmButtonText: 'Delete',
}).then((result) => {
    if (result.isConfirmed) {
        window.livewire.emit('deleteVersionPermanently', category_id);
    }
})
}

function confirmDeleteDescription(description_id) {

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn bg-gradient-danger m-2',
        cancelButton: 'btn bg-gradient-primary m-2',
    },
    buttonsStyling: false,
    background: '#3442b4',
})
swalWithBootstrapButtons.fire({
    title: '<span style="color: white;">Are you sure?</span>',
    html: "<span style='color: white;'>Want to delete Description permanently!</span>",
    showCancelButton: true,
    confirmButtonText: 'Delete',
}).then((result) => {
    if (result.isConfirmed) {
        window.livewire.emit('deleteDescriptionPermanently', description_id);
    }
})
}



    </script>


@endpush
