<div>
    <div class="table-responsive table-header-text w-100 pt-4 table-orange-color">
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="text-color-blue">
                <th>#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($announcements as $key => $announcement)
                <tr class="text-color-blue">
                    <td class="text-sm font-weight-normal">{{ $key + 1 }}</td>
                    <td class="text-sm font-weight-normal">{{ $announcement['title'] }}</td>
                    <td class="text-sm font-weight-normal">
                        @if(strlen(strip_tags($announcement['description'])) > 40)
                            {!! Str::limit(strip_tags($announcement['description']), 50, '') !!}
                            <a data-bs-toggle="modal"
                               data-bs-target="#viewAnnouncementNewsModal{{ $announcement['id'] }}"
                               style="color: #1b3a62; cursor: pointer; font-size: larger; font-weight: bold">
                                view more...
                            </a>
                        @else
                            {!! $announcement['description'] !!}
                        @endif
                    </td>
                    <td class="text-sm font-weight-normal">
                        <a onclick="deleteTutorial({{ $announcement['id'] }})" class="btn-sm mt-2 mb-0"
                           style="background:#ff0000;color:white;font-weight:bolder;cursor:pointer;">Delete</a>
                        <a wire:click="editAnnouncementModal({{ $announcement['id'] }},`{{ $announcement['title']  }}`,`{{ $announcement['description']  }}`)"
                           class="btn-sm mt-2 mb-0"
                           data-bs-target="#editFormAnnouncementNewsModal" data-bs-toggle="modal"
                           style="background:#1b3a62;color:white;font-weight:bolder;cursor:pointer;">Edit</a>
                    </td>
                </tr>

                <div wire:ignore.self class="modal fade" id="viewAnnouncementNewsModal{{ $announcement['id'] }}"
                     tabindex="-1"
                     role="dialog"
                     aria-labelledby="viewAnnouncementNewsModal{{ $announcement['id'] }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body" style=" border-radius: 9px">
                                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                        aria-label="Close" id="close-query-view-modal-{{$announcement['id']}}">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <form wire:submit.prevent="">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label"
                                                       style="color: #0f1534; font-size: 22px">Description:</label>
                                                <span
                                                    style="font-size: 18px;font-weight: 600;display: flex;background-color: #1b3a62; padding: 10px; border-radius: 5px; text-align: justify;">{!! $announcement['description'] !!}</span>

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
    <div wire:ignore.self class="modal fade" id="addAnnouncementNews" tabindex="-1"
         role="dialog"
         aria-labelledby="addAnnouncementNews" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Announcement & News</label>

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
                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                                               type="text"
                                               wire:model="title"
                                               placeholder="Enter announcement & news title">
                                        @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="col-12 mt-4">
                                        <label class="form-label" style="color: #1b3a62">Description</label>
                                        <div wire:ignore>
                                            <textarea id="summernote" class="form-control input-form-style editor"
                                                      placeholder="Enter description"
                                                      rows="3">{{ $description  }}</textarea>
                                        </div>
                                        @error('description') <small
                                            class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62">Create Announcement & News
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--    edit network tutorial model   --}}
    <div wire:ignore.self class="modal fade" id="editFormAnnouncementNewsModal" tabindex="-1" role="dialog"
         aria-labelledby="editFormAnnouncementNewsModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Announcement & News</label>
                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        @include('layouts.message')
                        <form wire:submit.prevent="updateForm">
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Title</label>
                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                                               type="text" wire:model="title" placeholder="Enter tutorial title">
                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                                               hidden="hidden" type="text" wire:model="tutorialId">
                                    </div>
                                    <div class="col-12 mt-4">
                                        <label class="form-label" style="color: #1b3a62">Description</label>
                                        <div wire:ignore><textarea id="edit-summernote"
                                                                   class="form-control input-form-style editor"
                                                                   wire:ignore wire:model="description"
                                                                   placeholder="Enter description"
                                                                   rows="3">{{ $description }}</textarea></div>
                                        @error('description') <small
                                            class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62">Edit Announcement & News
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
                html: "<span style='color: white;'>Want to delete Announcement & News</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteAnnouncementNews', id)
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

