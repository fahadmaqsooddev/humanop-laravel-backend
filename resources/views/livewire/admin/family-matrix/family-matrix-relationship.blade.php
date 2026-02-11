<div>
    <div class="table-responsive table-header-text w-100 pt-4 table-orange-color">
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="text-color-blue">
                <th>#</th>
                <th>Relationship</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($relationships as $key => $relationship)
                <tr class="text-color-blue">
                    <td class="text-sm font-weight-normal">{{ $key + 1 }}</td>
                    <td class="text-sm font-weight-normal">{{ $relationship->relationship_name }}</td>
                    <td class="text-sm font-weight-normal">
                        <a onclick="deleteRelationship({{ $relationship->id }})" class="btn-sm mt-2 mb-0"
                           style="background:#ff0000;color:white;font-weight:bolder;cursor:pointer;">Delete</a>
                        <a wire:click="editRelationshipModal({{ $relationship->id }}, `{{ strip_tags($relationship->relationship_name) }}`)"
                           class="btn-sm mt-2 mb-0"
                           data-bs-target="#editFormRelationshipModal" data-bs-toggle="modal"
                           style="background:#1b3a62;color:white;font-weight:bolder;cursor:pointer;">
                            Edit
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{--    add relationship tutorial model   --}}
    <div wire:ignore.self class="modal fade" id="addFamilyMatrixRelationship" tabindex="-1"
         role="dialog"
         aria-labelledby="addFamilyMatrixRelationship" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Family Matrix</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="submitForm">
                            <div class="card-body pt-0">
                                <div class="row">

                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Relationship Name</label>
                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                                               type="text"
                                               wire:model="relationship_name"
                                               placeholder="Enter Relationship Name">
                                        @error('relationship_name') <small
                                            class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62">Create Relationship
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--    edit relationship tutorial model   --}}
    <div wire:ignore.self class="modal fade" id="editFormRelationshipModal" tabindex="-1"
         role="dialog"
         aria-labelledby="editFormRelationshipModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Family Matrix</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="updateForm">
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Relationship Name</label>
                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                                               type="text"
                                               wire:model="relationshipName"
                                               placeholder="Enter Relationship Name">
                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                                               hidden="hidden" type="text"
                                               wire:model="relationshipId">
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62">Edit Network Tutorial
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('javascript')
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>

        function deleteRelationship(id) {

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
                html: "<span style='color: white;'>Want to delete Relationship</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteRelationship', id)
                }
            })
        }

    </script>
@endpush

