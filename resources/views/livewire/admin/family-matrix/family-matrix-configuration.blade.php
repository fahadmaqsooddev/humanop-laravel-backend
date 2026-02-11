
<div>
    <div class="table-responsive table-header-text w-100 pt-4 table-orange-color">
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="text-color-blue text-center">
                <th>#</th>
                <th>Grid Name</th>
                <th>Color</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if($configurations)
                @foreach($configurations as $key => $config)
                    <tr class="text-color-blue text-center">
                        <td class="text-sm  font-weight-normal">{{ $key + 1 }}</td>
                        <td class="text-sm font-weight-normal">{{ $config->grid_name }}</td>
                        <td class="text-sm font-weight-normal">{{ $config->color_code }}</td>
                        <td class="text-sm font-weight-normal">{{ strip_tags($config->text) }}</td>
                        <td class="text-sm font-weight-normal">
                            <a wire:click="editConfigurationModal({{ $config->id }}, '{{ $config->text }}')"
                               class="btn-sm mt-2 mb-0"
                               data-bs-target="#editFormConfigurationModal" data-bs-toggle="modal"
                               style="background:#1b3a62;color:white;font-weight:bolder;cursor:pointer;">
                                Edit
                            </a>

                        </td>
                    </tr>
              @endforeach
            @endif
    </tbody>
    </table>
    </div>


    {{--    edit family matrix tutorial model   --}}
    <div wire:ignore.self class="modal fade" id="editFormConfigurationModal" tabindex="-1"
         role="dialog"
         aria-labelledby="editFormConfigurationModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Family Matrix Configuration</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        @include('layouts.message')

                        <form wire:submit.prevent="updateConfiguration">
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Configuration Text</label>
                                        <textarea class="form-control input-form-style"
                                                  rows="5"
                                                  wire:model="configText"
                                                  ></textarea>
                                        <input type="hidden" wire:model="configId">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62">Update Configuration
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
            window.addEventListener('show-edit-modal', event => {
                $('#editFormConfigurationModal').modal('show');
            });

            window.addEventListener('hide-edit-modal', event => {
                $('#editFormConfigurationModal').modal('hide');
            });
        </script>

    @endpush

