<div>
    <div class="table-responsive table-header-text w-100 pt-4 table-orange-color">
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="text-color-blue">
                <th>#</th>
                <th>Icon</th>
                <th>Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tutorials as $key => $tutorial)
                <tr class="text-color-blue">
                    <td class="text-sm font-weight-normal">{{ $key + 1 }}</td>
                    <td class="text-sm font-weight-normal"><img src="{{ asset($tutorial['icon_url']['url']) }}" style="height: 50px;"></td>
                    <td class="text-sm font-weight-normal">{{ $tutorial['title'] }}</td>
                    <td class="text-sm font-weight-normal">
                        @if(strlen(strip_tags($tutorial['description'])) > 40)
                            {!! Str::limit(strip_tags($tutorial['description']), 50, '') !!}
                            <a data-bs-toggle="modal"
                               data-bs-target="#viewTutorialModal{{ $tutorial['id'] }}"
                               style="color: #1b3a62; cursor: pointer; font-size: larger; font-weight: bold">
                                view more...
                            </a>
                        @else
                            {!! $tutorial['description'] !!}
                        @endif
                    </td>
                    <td class="text-sm font-weight-normal">
                        <a onclick="deleteTutorial({{ $tutorial['id'] }})" class="btn-sm mt-2 mb-0"
                           style="background:#ff0000;color:white;font-weight:bolder;cursor:pointer;">Delete</a>
{{--                        <a data-bs-toggle="modal" data-bs-target="#subadmindetail{{ $tutorial['id'] }}"--}}
{{--                           class="btn-sm mt-2 mb-0"--}}
{{--                           style="background:#1B3A62;color:white;font-weight:bolder;margin-right:1rem;">View</a>--}}
                    </td>
                </tr>

                {{--    add network tutorial model   --}}
                <div wire:ignore.self class="modal fade" id="viewTutorialModal{{ $tutorial['id'] }}" tabindex="-1" role="dialog"
                     aria-labelledby="viewTutorialModal{{ $tutorial['id'] }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body" style=" border-radius: 9px">
                                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                        aria-label="Close" id="close-query-view-modal-{{$tutorial['id']}}">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <form wire:submit.prevent="">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label fs-6" style="color: #0f1534">Description:</label>
                                                <span style="color: white;font-size: 18px;font-weight: 600;display: flex;">{!! $tutorial['description'] !!}</span>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
            </tbody>
        </table>
    </div>

    {{--    add network tutorial model   --}}
    <div wire:ignore.self class="modal fade" id="addNetworkTutorial" tabindex="-1"
         role="dialog"
         aria-labelledby="addNetworkTutorial" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Network Tutorial</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="submitForm">
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Title</label>
                                        <input style="background-color: #eaf3ff;" class="form-control" type="text"
                                               wire:model="title"
                                               placeholder="Enter tutorial title">
                                        @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Icon</label>
                                        <input style="background-color: #eaf3ff;" class="form-control" type="file"
                                               wire:model="icon"
                                               placeholder="Choose tutorial icon">
                                        <span wire:loading.flex wire:target="icon"
                                              style="color: #1b3a62">Uploading ...</span>
                                        @error('icon') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Description</label>
                                        <div wire:ignore>
                                            <textarea id="summernote" class="form-control editor"
                                                      placeholder="Enter description"
                                                      rows="3">{{ $description }}</textarea>
                                        </div>
                                        @error('description') <small
                                            class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62">Create Network Tutorial
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

        function deleteTutorial(id) {

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
                html: "<span style='color: white;'>Want to delete Tutorial</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteTutorial', id)
                }
            })
        }

        document.addEventListener('livewire:load', function () {
            $('#summernote').summernote({
                height: 200,
                callbacks: {
                    onChange: function(contents, $editable) {
                    @this.set('description', contents);
                    }
                }
            });

            Livewire.hook('message.processed', (message, component) => {
                // Re-init if needed after Livewire DOM updates
                if (!$('#summernote').next().hasClass('note-editor')) {
                    $('#summernote').summernote({
                        height: 200,
                        callbacks: {
                            onChange: function(contents, $editable) {
                            @this.set('description', contents);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush

