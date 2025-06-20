@push('css')
    <style>
        .note-toolbar .note-btn {
            font-size: 16px !important; /* Text size */
            padding: 8px 12px !important; /* Button padding */
        }

        .note-editing-area {
            color: #0f1534;
        }

        .note-editor .note-toolbar {
            height: auto;
            min-height: 50px;
        }

        .note-toolbar {
            zoom: 1.2; /* Overall scale (optional) */
        }
    </style>
@endpush

<div class="row mt-4 container-fluid">
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    @if (!empty($versionId))
                        <h5 class="mb-0" style="color: #1b3a62">Edit Version Control</h5>
                    @else
                        <h5 class="mb-0" style="color: #1b3a62">Create Version Control</h5>
                    @endif
                </div>
                <div class="card-body pt-0">
                    <form wire:submit.prevent="storeVersionAndDescription" style="">
                        @include('layouts.message')
                        <!-- Version Field -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <label class="form-label" style="color: #1b3a62">Version</label>
                                <input style="background-color: #eaf3ff;color:#1b3a62" wire:model="version"
                                       class="form-control table-header-text" type="text">
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <label class="form-label" style="color: #1b3a62">Note</label>
                                <div wire:ignore>
                                    <textarea class="form-control " id="editor" wire:model='note'
                                              style="background-color: #eaf3ff; color: #1b3a62;" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn text-white fw-bolder" style="background-color: #1b3a62"
                                    wire:click="addVersionField">
                                <span
                                    style="font-weight: bolder;font-size:1rem;">Add More Features and  Descriptions</span>
                            </button>
                        </div>

                        @foreach ($versionDetails as $index => $detail)
                            <div class="row mb-3">
                                <!-- Version Type Checkboxes -->
                                <div class="col-md-12 mb-3">
                                    <label class="mb-1" style="color: #1b3a62">Select Type</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               wire:model="versionDetails.{{ $index }}.type"
                                               value="Web"
                                               id="web_{{ $index }}">
                                        <label class="form-check-label" for="web_{{ $index }}">Web</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               wire:model="versionDetails.{{ $index }}.type"
                                               value="App"
                                               id="app_{{ $index }}">
                                        <label class="form-check-label" for="app_{{ $index }}">App</label>
                                    </div>
                                </div>
                                <!-- Description -->
                                <div class="col-md-12 mt-3">
                                    <label class=" mb-1" style="color: #1b3a62">Description</label>
                                    <div wire:ignore>
                                        <textarea class="form-control editor" data-index="{{ $index }}"
                                                  wire:model='versionDetails.{{ $index }}.description'
                                                  data-property="versionDetails.{{ $index }}.description"
                                                  style="background-color: #0f1534; color: #1b3a62;"
                                                  rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="version_heading" class="form-label" style="color: #1b3a62">Select
                                        Version
                                        Heading</label>
                                    <select wire:model='versionDetails.{{ $index }}.version_heading'
                                            style="background-color: #eaf3ff;" class="form-control">
                                        <option value="">Select Option</option>
                                        <option value="0">Issue Fixed</option>
                                        <option value="1">New Feature</option>
                                    </select>
                                </div>
                                @if (count($versionDetails) > 1)
                                    <div class="col-md-2 d-flex align-items-end mt-2">
                                        <button type="button" class="btn btn-danger btn-sm"
                                                wire:click="removeVersionField({{ $index }})">
                                            <span>-</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <!-- Submit Button -->
                        @if(!empty($versionId))
                            <div class="text-end">
                                <button type="submit" class="btn btn-sm text-white" style="background-color: #1b3a62;">
                                    Update Version
                                </button>
                            </div>
                        @else
                            <div class="text-end">
                                <button type="submit" class="btn btn-sm text-white" style="background-color: #1b3a62;">
                                    Save Version
                                </button>
                            </div>
                        @endif
                    </form>
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
        document.addEventListener('livewire:load', function () {

            function initSummernote() {
                $('#editor').each(function () {
                    if (!$(this).hasClass('summernote-initialized')) {
                        $(this).summernote({
                            height: 200,
                            callbacks: {
                                onChange: function (contents, $editable) {

                                    const property = $(this).val();
                                    Livewire.emit('updateNote', property)

                                }
                            }
                        });
                        $(this).addClass('summernote-initialized'); // Avoid re-initializing
                    }
                });
            }

            initSummernote();

            Livewire.hook('message.processed', (message, component) => {
                initSummernote();
            });

            $('.createForm').on('click', function () {
                $('.editor').each(function () {
                    $(this).summernote('reset');
                });
            });

        });
    </script>
    <script>
        document.addEventListener('livewire:load', function () {

            function initSummernote() {
                $('.editor').each(function () {
                    if (!$(this).hasClass('summernote-initialized')) {
                        $(this).summernote({
                            height: 200,
                            callbacks: {
                                onChange: function (contents, $editable) {
                                    const index = $(this).data('index');
                                    if (index !== undefined) {
                                        Livewire.emit('updateDescription', index, contents);
                                    }
                                }
                            }

                        });
                        $(this).addClass('summernote-initialized');
                    }
                });
            }

            initSummernote();

            Livewire.hook('message.processed', (message, component) => {
                initSummernote();
            });

            $('.createForm').on('click', function () {
                $('.editor').each(function () {
                    $(this).summernote('reset');
                });
            });

        });
    </script>

@endpush
