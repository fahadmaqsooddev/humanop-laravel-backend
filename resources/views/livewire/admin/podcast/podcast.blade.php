<div>
    <div class="table-responsive table-header-text w-100 pt-4 table-orange-color">
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="text-color-blue">
                <th>#</th>
                <th>Title</th>
                <th>Audio File</th>
                <th style="display: flex; justify-content: center">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($podcasts as $key => $podcast)
                <tr class="text-color-blue">
                    <td class="text-sm font-weight-normal" style="align-items: center">{{ $key + 1 }}</td>
                    <td class="text-sm font-weight-normal">{{ $podcast['title'] }}</td>
                    <td class="text-sm font-weight-normal">
                        <audio controls style="width: 100%;">
                            <source src="{{ $podcast['audio_url']['path'] }}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </td>
                    <td class="text-sm font-weight-normal" style="display: flex; justify-content: center">
                        <a onclick="deleteTutorial({{ $podcast['id'] }})" class="btn-sm mt-2 mb-0"
                           style="background:#ff0000;color:white;font-weight:bolder;cursor:pointer;">Delete</a>
                        <a wire:click="editTutorialModal({{ $podcast['id'] }},`{{ $podcast['title']  }}`)"
                           class="btn-sm mt-2 mb-0"
                           data-bs-target="#editFormTutorialModal" data-bs-toggle="modal"
                           style="background:#1b3a62;color:white;font-weight:bolder;cursor:pointer;margin-left: 10px">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{$podcasts->links()}}

    {{--    add Podcast model   --}}
    <div wire:ignore.self class="modal fade" id="addPodcast" tabindex="-1"
         role="dialog"
         aria-labelledby="addPodcast" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Add Podcast Audio</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="submitForm">
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-12 mt-4">
                                        <label class="form-label" style="color: #1b3a62">Title</label>
                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                                               type="text" wire:model="title"
                                               placeholder="Enter tutorial title">
                                        @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="col-12 mt-4">
                                        <label class="form-label" style="color: #1b3a62">
                                            Upload Audio File [MP3, WAV AND MPEG]
                                        </label>
                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                                               type="file" Wire:model="audio_file" placeholder="Choose audio file"
                                               id="audioInput" accept="audio/*">
                                        <span wire:loading.flex wire:target="audio_file" style="color: #1b3a62">Uploading ...</span>
                                        @error('audio_file')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                </div>
                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62">Create Podcast
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--    --}}{{--    edit network tutorial model   --}}
    {{--    <div wire:ignore.self class="modal fade" id="editFormTutorialModal" tabindex="-1"--}}
    {{--         role="dialog"--}}
    {{--         aria-labelledby="editFormTutorialModal" aria-hidden="true">--}}
    {{--        <div class="modal-dialog modal-lg" role="document">--}}
    {{--            <div class="modal-content">--}}
    {{--                <div class="modal-body" style=" border-radius: 9px">--}}
    {{--                    <div class="card-body pt-0">--}}
    {{--                        <label class="form-label fs-4" style="color: #1b3a62">Network Tutorial</label>--}}

    {{--                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"--}}
    {{--                                aria-label="Close">--}}
    {{--                            <span aria-hidden="true">&times;</span>--}}
    {{--                        </button>--}}
    {{--                        @include('layouts.message')--}}
    {{--                        <form wire:submit.prevent="updateForm">--}}
    {{--                            <div class="card-body pt-0">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-12">--}}
    {{--                                        <label class="form-label" style="color: #1b3a62">Title</label>--}}
    {{--                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"--}}
    {{--                                               type="text"--}}
    {{--                                               wire:model="title"--}}
    {{--                                               placeholder="Enter tutorial title">--}}
    {{--                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"--}}
    {{--                                               hidden="hidden" type="text"--}}
    {{--                                               wire:model="tutorialId">--}}
    {{--                                    </div>--}}

    {{--                                    <div class="col-12">--}}
    {{--                                        <label class="form-label" style="color: #1b3a62">Icon</label>--}}
    {{--                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"--}}
    {{--                                               type="file" wire:model="icon"--}}
    {{--                                               placeholder="Choose tutorial icon" id="iconInput" x-ref="iconInput">--}}
    {{--                                        <span wire:loading.flex wire:target="icon"--}}
    {{--                                              style="color: #1b3a62">Uploading ...</span>--}}
    {{--                                        @error('icon') <small class="text-danger">{{ $message }}</small> @enderror--}}
    {{--                                    </div>--}}

    {{--                                    <div class="col-12">--}}
    {{--                                        <img src="{{ $tutorialIcon }}"--}}
    {{--                                             style="height: 100px; margin-top: 5px; margin-bottom: 5px">--}}
    {{--                                    </div>--}}

    {{--                                    <div class="col-12">--}}
    {{--                                        <label class="form-label" style="color: #1b3a62">Description</label>--}}
    {{--                                        <div wire:ignore>--}}
    {{--                                            <textarea id="edit-summernote" class="form-control input-form-style editor"--}}
    {{--                                                      wire:ignore wire:model="description" placeholder="Enter description"--}}
    {{--                                                      rows="3">{{ $description }}</textarea>--}}
    {{--                                        </div>--}}
    {{--                                        @error('description') <small--}}
    {{--                                            class="text-danger">{{ $message }}</small> @enderror--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}

    {{--                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"--}}
    {{--                                        style="background-color: #1b3a62">Edit Network Tutorial--}}
    {{--                                </button>--}}
    {{--                            </div>--}}
    {{--                        </form>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

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
                    onChange: function (contents, $editable) {
                    @this.set('description', contents)
                        ;
                    }
                }
            });

            Livewire.hook('message.processed', (message, component) => {
                // Re-init if needed after Livewire DOM updates
                if (!$('#summernote').next().hasClass('note-editor')) {
                    $('#summernote').summernote({
                        height: 200,
                        callbacks: {
                            onChange: function (contents, $editable) {
                            @this.set('description', contents)
                                ;
                            }
                        }
                    });
                }
            });

            // Reset Summernote content after form submission
            window.addEventListener('reset-summernote', () => {
                $('#summernote').summernote('reset'); // resets to empty
            });

            // Optional: clear summernote on "Create New" button click
            $('.createForm').on('click', function () {
                $('#summernote').summernote('reset');
            });

            window.addEventListener('reset-file-input', () => {
                document.getElementById('iconInput').value = null;
            });
        });

        document.addEventListener('livewire:load', function () {
            $('#edit-summernote').summernote({
                height: 200,
                callbacks: {
                    onChange: function (contents, $editable) {
                    @this.set('description', contents)
                        ;
                    }
                }
            });

            Livewire.hook('message.processed', (message, component) => {
                // Re-init if needed after Livewire DOM updates
                if (!$('#edit-summernote').next().hasClass('note-editor')) {
                    $('#edit-summernote').summernote({
                        height: 200,
                        callbacks: {
                            onChange: function (contents, $editable) {
                            @this.set('description', contents)
                                ;
                            }
                        }
                    });
                }
            });

            // Reset Summernote content after form submission
            window.addEventListener('reset-summernote', () => {
                $('#edit-summernote').summernote('reset'); // resets to empty
            });

            // Optional: clear summernote on "Create New" button click
            $('.createForm').on('click', function () {
                $('#edit-summernote').summernote('reset');
            });

            window.addEventListener('reset-file-input', () => {
                document.getElementById('iconInput').value = null;
            });

            // Set description when editing
            window.addEventListener('set-edit-description', event => {
                $('#edit-summernote').summernote('code', event.detail.content);
            });

        });
    </script>
@endpush

