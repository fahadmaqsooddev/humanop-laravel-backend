<div>
    <div class="table-responsive table-header-text w-100 pt-4 table-orange-color">
        @include('layouts.message')
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="text-color-blue">
{{--                <th>#</th>--}}
                <th>Title</th>
                <th>Audio File</th>
                <th style="display: flex; justify-content: center">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($podcasts as $key => $podcast)
                <tr class="text-color-blue">
{{--                    <td class="text-sm font-weight-normal" style="align-items: center">{{ $key + 1 }}</td>--}}
                    <td class="text-sm font-weight-normal">
                        @if(strlen(strip_tags($podcast['title'])) > 10)
                            {!! Str::limit(strip_tags($podcast['title']), 10, '') !!}
                            <a data-bs-toggle="modal"
                               data-bs-target="#viewPodcastModal{{ $podcast['id'] }}"
                               style="color: #1b3a62; cursor: pointer; font-size: larger; font-weight: bold">
                                more...
                            </a>
                        @else
                            {!! $podcast['title'] !!}
                        @endif
                    </td>
                    <td class="text-sm font-weight-normal">
                        @if (!empty($podcast) && !empty($podcast['audio_url']['path']))
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-secondary seek-back" style="font-size: 12px;">⏪ 10s</button>

                                <audio controls class="audio-player" style="width: 100%;">
                                    <source src="{{ asset($podcast['audio_url']['path']) }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>

                                <button type="button" class="btn btn-sm btn-secondary seek-forward" style="font-size: 12px;">⏩ 10s</button>
                            </div>
                        @else
                            <span class="text-muted">No audio available</span>
                        @endif
                    </td>
                    <td class="text-sm font-weight-normal" style="display: flex; justify-content: center">
                        <a onclick="deletePodcast({{ $podcast['id'] }})" class="btn-sm mt-2 mb-0"
                           style="background:#ff0000;color:white;font-weight:bolder;cursor:pointer;">Delete</a>
                        <a wire:click="editPodcastModal({{ $podcast['id'] }},`{{ $podcast['title']  }}`,`{{ $podcast['audio_url']['path'] }}`)"
                           class="btn-sm mt-2 mb-0"
                           data-bs-target="#editFormPodcastModal" data-bs-toggle="modal"
                           style="background:#1b3a62;color:white;font-weight:bolder;cursor:pointer;margin-left: 10px">Edit</a>
                    </td>
                </tr>
                <div wire:ignore.self class="modal fade" id="viewPodcastModal{{ $podcast['id'] }}"
                     tabindex="-1"
                     role="dialog"
                     aria-labelledby="viewPodcastModal{{ $podcast['id'] }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body" style=" border-radius: 9px">
                                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                        aria-label="Close" id="close-query-view-modal-{{$podcast['id']}}">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <form wire:submit.prevent="">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label"
                                                       style="color: #0f1534; font-size: 22px">Title:</label>
                                                <span
                                                    style="font-size: 18px;font-weight: 600;display: flex;color: #1b3a62; padding: 10px; border-radius: 5px; text-align: justify;">{!! $podcast['title'] !!}</span>

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
                                    <div class="col-12 mt-4">
                                        <label class="form-label" style="color: #1b3a62">
                                            Upload Thumbnail Image File [PNG, JPG, JPEG AND GIF]
                                        </label>
                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                                               type="file" Wire:model="thumbnail_file" placeholder="Choose audio file"
                                               id="audioInput" accept="image/*">
                                        <span wire:loading.flex wire:target="thumbnail_file" style="color: #1b3a62">Uploading ...</span>
                                        @error('thumbnail_file')
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

    {{--    edit Podcast model   --}}
    <div wire:ignore.self class="modal fade" id="editFormPodcastModal" tabindex="-1"
         role="dialog"
         aria-labelledby="editFormPodcastModal" aria-hidden="true">
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
                        <form wire:submit.prevent="updatePodcast">
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
</div>

@push('javascript')
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>

        function deletePodcast(id) {

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
                html: "<span style='color: white;'>Want to delete Podcast</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deletePodcast', id)
                }
            })
        }

        // document.addEventListener('livewire:load', function () {
        //     Livewire.on('closeModal', () => {
        //         // Wait 5 seconds (5000ms) before closing the modal
        //         setTimeout(function () {
        //             $('#addPodcast').modal('hide');
        //         }, 5000);
        //     });
        // });

        $(document).ready(function () {
            $('#addPodcast').on('hidden.bs.modal', function () {
                Livewire.emit('toggleCreatePodcastFormModal');
            });
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const audioPlayers = document.querySelectorAll('audio');

            audioPlayers.forEach(audio => {
                audio.addEventListener('play', function () {
                    // Pause all other audio elements
                    audioPlayers.forEach(otherAudio => {
                        if (otherAudio !== audio) {
                            otherAudio.pause();
                        }
                    });
                });
            });
        });
    </script>
    <script>
        function attachAudioSeekButtons() {
            document.querySelectorAll('.seek-back').forEach(button => {
                button.onclick = function () {
                    const audio = this.parentElement.querySelector('audio');
                    if (audio) {
                        audio.currentTime = Math.max(0, audio.currentTime - 10);
                    }
                };
            });

            document.querySelectorAll('.seek-forward').forEach(button => {
                button.onclick = function () {
                    const audio = this.parentElement.querySelector('audio');
                    if (audio) {
                        audio.currentTime = Math.min(audio.duration, audio.currentTime + 10);
                    }
                };
            });
        }

        document.addEventListener('DOMContentLoaded', attachAudioSeekButtons);
        document.addEventListener('livewire:update', attachAudioSeekButtons);
    </script>
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('refreshPage', () => {
                window.location.reload();
            });
        });
    </script>

@endpush

